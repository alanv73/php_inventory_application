<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"  && !empty($_POST['uname'])) {
        
        // POST variables
        $form_username = filter_var($_POST['uname'], FILTER_SANITIZE_STRING);
        $form_password = $_REQUEST['pword'];

        // Remote IP Address
        $current_location = $_SERVER['REMOTE_ADDR'];

        // Database Connection
        require('config.php');

        // Query database
        $stmt = $conn->prepare(
            "SELECT * FROM users WHERE username = ? or email = ?");

        $stmt->bind_param("ss", $form_username, $form_username);

        $stmt->execute();
        $result = $stmt->get_result();

        $stored_hash = "";

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $stored_username = $row['username'];
                $stored_hash = $row['password'];
                // get last login location from database
                $stored_location = $row['location'];
            }
        } else {
            echo "Invalid Log In. Try Again.<br />";
        }

        $stmt->close();

        if (password_verify($form_password, $stored_hash)) {
            $_SESSION['username'] = $form_username;

            // Compare last login IP to current login IP address
            if ($current_location != $stored_location){
                $location_message = "You are logging in from a different location <br />";

                // Update new IP address location
                $sql = "UPDATE users SET location = '$current_location' WHERE username = '$stored_username';";
                $result = $conn->query($sql);
            }

        }

        //Close database connection
        $conn->close();

        //
        if($_SESSION['username'] != null){
            setcookie("user_cookie", $_SESSION['username'], time() + (180), "/");
            header("Location: showproducts.php");
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container login-form">
        <div class="form-div">
            <h1>Login</h1>
            <form action="" method="POST">
                <label for="uname">Username or email: </label>
                <input type="text" name="uname" placeholder="username" autofocus> <br />

                <label for="uname">Password :</label>
                <input class="pword-login" type="password" name="pword" placeholder="password"> <br />
                
                <button type="submit">Submit</button>
                <button type="reset">Reset</button>
            </form>
            <p><a href="register.php">Register</a> a new account</p>
        </div>
    </div>
    <footer>
        <p>
            &copy; <?php echo date("Y"); ?>&nbspAlan Van Art
        </p>
    </footer>

</body>