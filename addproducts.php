<?php
    require('header.php');

    if(isset($_SESSION['username']) && 
            $_SESSION['username'] != null){
        // if we are logged in...
    } else {
        // if we are not logged in send status code 302
        // back to browser to redirect to login page
        header("Location: login.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && 
            filter_var($_POST['cost'], FILTER_VALIDATE_FLOAT) &&
            filter_var($_POST['onhand'], FILTER_VALIDATE_INT)){
        $product = filter_var($_POST['product'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        $cost = $_POST['cost'];
        $on_hand = filter_var($_POST['onhand'], FILTER_SANITIZE_NUMBER_INT);
        $image_url = filter_var($_POST['imageurl'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        $date_now = date('Y-m-d H:i:s');

        $sql = "insert into inventory " . 
            "(product_name, product_cost, on_hand, image_url, last_updated) " . 
            "values('$product', $cost, $on_hand, '$image_url', '$date_now');";
        
        require('config.php');
        $result = $conn->query($sql);

        if($result){
            $new_product = true;
        } else {
            $new_product = false;
        }

        $conn->close();
    }
?>
<div class="container add-products">
    <h1>Add Products</h1>
    <form action="" method="post">
        <label for="product">Product Name</label>
        <input type="text" name="product" size="40" placeholder="Product Name"><br>
        <label for="cost">Product Cost</label>
        $<input type="number" step=".01" style="width: 75px;" name="cost" min="0" placeholder="0.00"><br>
        <label for="onhand">Qty On-Hand</label>
        <input type="number" name="onhand" style="width: 50px;" min="0" placeholder="0"><br>
        <label for="imageurl">Image URL</label>
        <input type="text" name="imageurl"placeholder="Image URL"><br>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
    </form>
    <?php
        if(isset($new_product)){
            if($new_product){ ?>
                <p>New Product Added Successfully</p>
    <?php
            } else { ?>
                <p>An error occurred. Product not added.</p>
    <?php
            }
        }
    ?>
</div>  
<?php
    require('footer.php');
?>