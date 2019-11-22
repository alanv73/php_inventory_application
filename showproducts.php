<?php
    define("INCREMENT", true);
    define("DECREMENT", false);
    require('header.php');
    require('config.php');

    // modify the on_hand value of a record
    // parameters: $record_pk - primary key value of item
    //              $action - true for increment, false for decrement
    function qtyUpdate($record_pk, $action){
        global $conn;

        if($action){
            // increment
            $sql = "update inventory set " .
                    "on_hand = on_hand + 1, " .
                    "last_updated = now() " . 
                    "where id = $record_pk;";
        } else {
            // decrement
            $sql = "update inventory set " .
                    "on_hand = on_hand - 1, " .
                    "last_updated = now() " .
                    "where id = $record_pk and " .
                    "on_hand > 0;";
        }

        $conn->query($sql);
    }


    if(!isset($_SESSION['username']) || 
            $_SESSION['username'] == null){
        // if we are not logged in send status code 302
        // back to browser to redirect to login page
        header("Location: login.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" &&
            (isset($_POST['plus']) || isset($_POST['minus']))){
                if(isset($_POST['item_to_mod'])){
                    $selected_item = $_POST['item_to_mod'];
                }
                
                if(isset($_POST['plus'])){
                    qtyUpdate($selected_item, INCREMENT);
                } 
                
                if(isset($_POST['minus'])) {
                    qtyUpdate($selected_item, DECREMENT);
                }
            }

    $sql = "select * from inventory;";

    $results = $conn->query($sql);
?>
<div class="container show-products">
    <h1>Inventory List</h1>
    <table border="1px;" class="tftable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Product Cost</th>
                <th>Quantity On-Hand</th>
                <th>Product Image</th>
                <th>Last Updated</th>
                <th>Actions</th>
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
                        <img src="images/imagenotfound.jpg" onerror="this.onerror=null; this.src='https://equipmentsearch.modernmachinery.com/images/model/NotFound.png'">
                    <?php } ?>
                </td>
                <td><span class="timestamp"><?php echo $row['last_updated']; ?></span></td>
                <td class="action">
                    <form action="" method="post">
                        <input type="submit" name="plus" value="+">
                        <input type="hidden" name="item_to_mod" value="<?php echo $row['id']; ?>">
                    </form>
                    <form action="" method="post">
                        <input type="submit" name="minus" value="-">
                        <input type="hidden" name="item_to_mod" value="<?php echo $row['id']; ?>">
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>  
<?php
    $conn->close();
    require('footer.php');
?>