<?php
    require('header.php');
    require('config.php'); // db connection info - setup $conn

    function isAuthenticated($uname, $pword){
        global $conn;
        $auth = false;
        $sql = "select * from users where username = ? limit 1;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $uname); // bind values to the statement; s=string
        $stmt->execute(); // run the prepared statement with variable values inserted
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $stored_hash = $row['password'];
            }

            if (password_verify($pword, $stored_hash)) {
                $auth = true;
            }
        } else {
            $auth = false;
        }
        
        $stmt->close();
        return $auth;
    }

    function change_password($user, $new_pword){
        global $conn;
        $completed = false;
        $new_hash = password_hash($new_pword, PASSWORD_BCRYPT);
        $sql = "update users set password = ? where username = ?;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_hash, $user); // bind values to the statement; s=string
        $result = $stmt->execute(); // run the prepared statement with variable values inserted

        if($result){
            $completed = true;
        }

        return $completed;
    }

    if(!isset($_SESSION['username']) || 
            $_SESSION['username'] == null) { 
        header("Location: login.php");
    } else {
        $username = $_SESSION['username'];
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $old_password = $_POST['old_pword'];
        $new_password = $_POST['pword'];
        $confirm_password = $_POST['cpword'];

        if($new_password === $confirm_password){
            if(isAuthenticated($username, $old_password)){
                if(change_password($username, $new_password)){
                    // success notification
                    echo "<div class='alert' role='alert'>Password successfully changed!</div>";
                } else {
                    // failure notification
                    echo "<div class='alert' role='alert'>Password not modified</div>";
                }
            } else {
                // failure notification
                echo "<div class='alert' role='alert'>Incorrect Old Password</div>";
            }
        } else {
        // otherwise warn the user that their passwords don't match
        echo "<div class='alert' role='alert'>Passwords don't match!</div>";
        }
    }
?>
<div class="container pass-change add-products">
    <h1>Change Password</h1>
    <div class="first-row">
        <label for="old_pword">Username: </label>
        <label for="old_pword"><?php echo $username; ?></label>
    </div>
    <form action="" method="post">
        <div class="row">
            <label for="old_pword">Old Password: </label>
            <input type="password" name="old_pword">
        </div>
        <div class="row">
            <label for="pword">New Password: </label>
            <input type="password" name="pword">
        </div>
        <div class="row">
            <label for="cpword">Confirm Password: </label>
            <input type="password" name="cpword">
        </div>
        <div class="row" id="buttons">
            <button type="submit">Submit</button>
            <button type="reset">Reset</button>
        </div>
    </form>
</div>
<?php
    require('footer.php');
?>