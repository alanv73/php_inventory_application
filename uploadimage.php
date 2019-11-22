<?php
    require('header.php');

    // if not logged in redirect to login page
    if(isset($_SESSION['username']) && 
            $_SESSION['username'] != null){
        // if we are logged in...
        // echo "Welcome " . $_SESSION['username'];
        // echo "<br />";
    } else {
        // if we are not logged in send status code 302
        // back to browser to redirect to login page
        header("Location: login.php");
    }

    

    // check if we are posting data - was submit button clicked
    if(isset($_POST["submit"])) {
        $target_dir = "images/";

        if (isset($_FILES["fileToUpload"]["name"])){
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        }

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if(!file_exists($target_dir)){
            mkdir($target_dir);
        }

        // only allow jpg & png
        if($imageFileType != "jpg" && 
                $imageFileType != "png" ) {
            echo "Sorry, only JPG, & PNG files are allowed.<br>";
            $uploadOk = 0;
        }

        // Check if image file has a size
        if($_FILES["fileToUpload"]["tmp_name"] != ""){
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                // echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.<br>";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file) && 
                !isset($_POST['overwrite'])) {
            echo "Sorry, file already exists.<br>";
            $uploadOk = 0;
        }

        // Check file size - no images over 1MB
        if ($_FILES["fileToUpload"]["size"] > 1000000) {
            echo "Sorry, your file is too large.<br>";
            $uploadOk = 0;
        }

        // if $uploadOk is set - accept the file
        if ($uploadOk != 0) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }

    }



?>

<div class="container">
    <h1>Upload Image</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Select image to upload:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" class="uploadbutton">
        <br>
        <input type="checkbox" name="overwrite" value="replace_file">Replace File
        <input type="submit" value="Upload Image" name="submit">
    </form>
</div>  
<?php
    require('footer.php');
?>