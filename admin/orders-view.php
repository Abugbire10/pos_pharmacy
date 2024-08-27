<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Order View</h4>  
             <a href="orders-view-print.php?track=<?= $_GET['track'] ?>" class="btn btn-info mx-2 btn-sm float-end">Print</a>
             <a href="orders.php" class="btn btn-danger mx-2 btn-sm float-end">Back</a>
        </div>
        <div class="cad-body">
            <?php alertMessage(); ?>

            <?php 
                if(isset($_GET['track']))
                {
                    if($_GET['track'] == ''){
                        ?>
                           <div class="text-center py-5">
                            <h5>No Tracking Number Found!</h5>
                            <div>
                                <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back to Orders</a>
                            </div>
                            </div>
                        <?php
                        return false;
                    }

                    $trackingNo = validate(($_GET['track']));

                    $query = "SELECT O.*, c.* FROM orders o, customers c 
                                WHERE c.id= o.customer_id AND tracking_no='$trackingNo' 
                                ORDER BY o.id DESC";
                    $orders = mysqli_query($conn, $query);
                    if($orders)
                    {
                        if(mysqli_num_rows($orders) > 0){
                            $orderData = mysqli_fetch_assoc($orders);
                            $orderId = $orderData['id'];

                            ?>
                                <div class="card card-body shadow border-1 mb-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>Order Details</h4>
                                            <label class="mb-1">
                                                Tracking No: <span class="fw-bold"><?= $orderData['tracking_no']; ?></span>
                                            </label>
                                            <br>
                                            <label class="mb-1">
                                                Order Date: <span class="fw-bold"><?= $orderData['order_date']; ?></span>
                                            </label>
                                            <br>
                                            <label class="mb-1">
                                                Order Status: <span class="fw-bold"><?= $orderData['order_status']; ?></span>
                                            </label>
                                            <br>
                                            <label class="mb-1">
                                                Payment Mode: <span class="fw-bold"><?= $orderData['payment_mode']; ?></span>
                                            </label>
                                            <br>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>Customer Details</h4>
                                            <label class="mb-1">
                                                Full Name: 
                                                <span class="fw-bold"><?= $orderData['name']; ?></span>
                                            </label>
                                            <br>
                                            <label class="mb-1">
                                                Email Address: 
                                                <span class="fw-bold"><?= $orderData['email']; ?></span>
                                            </label>
                                            <br>
                                            <label class="mb-1">
                                                Phone Number: 
                                                <span class="fw-bold"><?= $orderData['phone']; ?></span>
                                            </label>
                                            <br>
                                        </div>
                                    </div>
                                </div>

                        <!--code for viewing orders -->

<?php 
// Debugging line to check the tracking number being used
echo "Tracking No: " . htmlspecialchars($trackingNo); 

// Check if the order exists with that tracking number
$orderQuery = "SELECT * FROM orders WHERE tracking_no = '$trackingNo'";
$orderCheck = mysqli_query($conn, $orderQuery);

if ($orderCheck) {
    if (mysqli_num_rows($orderCheck) > 0) {
        $orderRow = mysqli_fetch_assoc($orderCheck);
        echo "Order found with Tracking No: " . htmlspecialchars($orderRow['tracking_no']); // Debugging line

        // Proceed to fetch order items using the order id
        $orderId = $orderRow['id'];
        $orderItemQuery = "SELECT oi.id as orderItemId, oi.product_id, oi.quantity, oi.price, 
                            p.name AS productName, p.image 
                            FROM order_items AS oi 
                            JOIN products AS p ON p.id = oi.product_id 
                            WHERE oi.order_id = '$orderId'";

        $orderItemsRes = mysqli_query($conn, $orderItemQuery);
        
        if ($orderItemsRes) {
            if (mysqli_num_rows($orderItemsRes) > 0) {
                ?>
                <h4 class="my-3">Order Items Details</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order Item ID</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalPrice = 0; // Initialize total price variable

                        while ($orderItemRow = mysqli_fetch_assoc($orderItemsRes)) : 
                            $itemTotal = $orderItemRow['price'] * $orderItemRow['quantity'];
                            $totalPrice += $itemTotal; // Add item total to the total price
                        ?>
                            <tr>
                                <td class="fw-bold text-center">
                                    <?= $orderItemRow['orderItemId']; ?>
                                </td>
                                <td class="fw-bold text-center">
                                    <?= $orderItemRow['product_id']; ?>
                                </td>
                                <td>
                                    <img src="<?= $orderItemRow['image'] != '' ? '../'.$orderItemRow['image'] : '../assets/images/no-img.jpg'; ?>"
                                        style="width: 50px; height: 50px;" 
                                        alt="Img" />
                                    <?= $orderItemRow['productName']; ?>
                                </td>
                                <td class="fw-bold text-center">
                                    <?= number_format($orderItemRow['price'], 0) ?>
                                </td>
                                <td class="fw-bold text-center">
                                    <?= $orderItemRow['quantity']; ?>
                                </td>
                                <td class="fw-bold text-center">
                                    <?= number_format($itemTotal, 0) ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total Price</td>
                            <td class="text-end fw-bold">GHC: <?= number_format($totalPrice, 0); ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php
            } else {
                echo '<h5>No Record Found!</h5>';
            }
        } else {
            echo 'Error in fetching order items: ' . mysqli_error($conn);
        }
    } else {
        echo 'No order found for tracking number: ' . htmlspecialchars($trackingNo);
    }
} else {
    echo 'Error in querying orders: ' . mysqli_error($conn);
}
?>


                        <!-- code for viewig orders


                            <?php
                        }else{
                            echo '<h5>No Record Found!</h5>';
                            return false;
                        }
                    }
                    else
                    {
                        echo '<h5>Something went wrong!</h5>';
                    }
                }
                else
                {
                    ?>
                        <div class="text-center py-5">
                            <h5>No Tracking Number Found!</h5>
                            <div>
                                <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back to Orders</a>
                            </div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div> 
                                                           
<?php include('includes/footer.php'); ?>
