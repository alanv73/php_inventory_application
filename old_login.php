<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"  && 
            !empty($_REQUEST['uname'])) {
        
        // POST variables
        $form_username = filter_var($_POST['uname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        $form_password = $_REQUEST['pword'];

        // get current login location
        $current_location = $_SERVER['REMOTE_ADDR'];

        // database connection - $conn variable
        require('config.php');

        // query database
        $sql = "select * from users where username = '$form_username' limit 1;";
        $result = $conn->query($sql);
        $stored_hash = "";

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $stored_username = $row['username'];
                $stored_hash = $row['password'];
                // get last login location from database
                $stored_location = $row['location'];
            }
        } else {
            echo "Invalid Log In. Try Again.<br>";
        }

        if (password_verify($form_password, $stored_hash)) {
            $_SESSION['username'] = $form_username;

            // check stored (last) login location against current login location
            if ($current_location != $stored_location){
                $location_message = "Logging in from a different location<br>";

                // update location stored in database to current location
                $sql = "update users set location = '$current_location' where username = '$stored_username';";
                $result = $conn->query($sql);
            }

        }

        $conn->close();

        if($_SESSION['username'] != null){
            header("Location: addproducts.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In</title>
</head>
<body>
    <h1>Inventory Login</h1>
    <form action="" method="post">
        <label for="uname">Username</label>
        <input type="text" name="uname" placeholder="username"><br>
        <label for="uname">Password</label>
        <input type="password" name="pword" placeholder="password"><br>
        <button type="reset">Reset</button>
        <button type="submit">Submit</button>
    </form>

</body>
</html>