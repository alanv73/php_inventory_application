<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_GET['logout'])){
        $logout = $_GET['logout'];

        if($logout === 'true'){
            $_SESSION['username'] = null;
        }
    }

    if(!isset($_SESSION['username']) || 
            $_SESSION['username'] == null){
        header("Location: login.php");
    }

    if(isset($_COOKIE["user_cookie"])){
        setcookie("user_cookie", $_SESSION['username'], time() + (180), "/");
    } else {
        $_SESSION['username'] = null;
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventory</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="nav">
        <ul>
            <li><a href="addproducts.php">Add</a></li>
            <li><a href="showproducts.php">Show</a></li>
            <li><a href="deleteproducts.php">Delete</a></li>
            <li><a href="uploadimage.php">Upload Image</a></li>
            <li id="second-last"><a href="header.php?logout=true">Logout</a></li>
            <li><a href="passwd.php">Change Password</a></li>
        </ul>
    </div>