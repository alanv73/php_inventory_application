<?php
    require('header.php');

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

    require('config.php');

    if($_SERVER["REQUEST_METHOD"] == "POST" &&
            isset($_POST['productid'])){
        
        $product_id = $_POST['productid'];
        $sql = "delete from inventory where id = $product_id;";

        $conn->query($sql);
    }

    $sql = "select * from inventory;";

    $results = $conn->query($sql);
?>
<div class="container">
    <div class="inner">
        <h1>Delete Products</h1>
        <table border="1px;" class="tftable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Product Cost</th>
                    <th>Quantity On-Hand</th>
                    <th>Product Image</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $results->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td class="description"><?php echo $row['product_name']; ?></td>
                    <td class="cost">$<?php echo $row['product_cost']; ?></td>
                    <td class="qty"><?php echo $row['on_hand']; ?></td>
                    <td class="prod_image">
                        <?php if ($row['image_url']){ ?>
                            <img src="<?php echo $row['image_url']; ?>" onerror="this.onerror=null; this.src='https://equipmentsearch.modernmachinery.com/images/model/NotFound.png'">
                        <?php } else { ?>
                            <img src="images/imagenotfound.jpg" width="200" onerror="this.onerror=null; this.src='https://equipmentsearch.modernmachinery.com/images/model/NotFound.png'">
                        <?php } ?>
                    </td>
                    <td><span class="timestamp"><?php echo $row['last_updated']; ?></span></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="inner">
        <h2>Delete Products</h2>
        <h3>Enter the Product ID number of the item you wish to delete</h3>
        <form action="" method="post">
            <label for="productid">Product ID</label>
            <input type="number" name="productid" style="width: 50px;" min="0" placeholder="0"><br>
            <button type="submit">Submit</button>
            <button type="reset">Reset</button>
        </form>
    </div>
</div>  
<?php
    $conn->close();
    require('footer.php');
?>