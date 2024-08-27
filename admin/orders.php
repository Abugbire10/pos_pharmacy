<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="mb-0">Purchases</h4>
                </div>
                <div class="col-md-8">
                    <form action="" method="GET">
                        <div class="row g-1">
                            <div class="col-md-4">
                                <input type="date" 
                                name="date"
                                class="form-control"
                                value="<?= isset($_GET['date']) ? $_GET['date'] : ''; ?>" />
                            </div>
                            <div class="col-md-4">
                                <select name="payment_status" class="form-select">
                                    <option value="">Select Payment Status</option>
                                    <option 
                                        value="Cash Payment"
                                        <?= isset($_GET['payment_status']) && $_GET['payment_status'] == 'Cash Payment' ? 'selected' : ''; ?>
                                    >Cash Payment</option>
                                    <option 
                                        value="Online Payment"
                                        <?= isset($_GET['payment_status']) && $_GET['payment_status'] == 'Online Payment' ? 'selected' : ''; ?>
                                    >Online Payment</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="orders.php" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>   
        </div>
        <div class="card-body">

        <?php 
            if(isset($_GET['date']) || isset($_GET['payment_status']) ){
                $orderDate = validate($_GET['date']);
                $paymentStatus = validate($_GET['payment_status']);

                if($orderDate != '' && $paymentStatus == ''){
                    $query = "SELECT o.*, c.* FROM orders o JOIN customers c ON c.id= o.customer_id WHERE o.order_date='$orderDate' ORDER BY o.id DESC";
                } elseif($orderDate == '' && $paymentStatus != ''){
                    $query = "SELECT o.*, c.* FROM orders o JOIN customers c ON c.id= o.customer_id WHERE o.payment_mode='$paymentStatus' ORDER BY o.id DESC";
                } elseif($orderDate != '' && $paymentStatus != ''){
                    $query = "SELECT o.*, c.* FROM orders o JOIN customers c ON c.id= o.customer_id WHERE o.order_date='$orderDate' AND o.payment_mode='$paymentStatus' ORDER BY o.id DESC";
                } else {
                    $query = "SELECT o.*, c.* FROM orders o JOIN customers c ON c.id= o.customer_id ORDER BY o.id DESC";
                }
            } else {
                $query = "SELECT o.*, c.* FROM orders o JOIN customers c ON c.id= o.customer_id ORDER BY o.id DESC";
            }

            $orders = mysqli_query($conn, $query);
            if($orders){
                if(mysqli_num_rows($orders) > 0){
                    ?>            
                    <table class="table table-striped table-bordered align-items-center justify-content-center">
                        <thead>
                            <tr>
                                <th>Tracking No.</th>
                                <th>C Name</th>
                                <th>C Phone</th>
                                <th>Order Date</th>
                                <th>Order Status</th>
                                <th>Payment Mode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $orderItem) : ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($orderItem['tracking_no']); ?></td>
                                    <td><?= htmlspecialchars($orderItem['name']); ?></td>
                                    <td><?= htmlspecialchars($orderItem['phone']); ?></td>
                                    <td><?= date('d M, Y', strtotime($orderItem['order_date'])); ?></td>
                                    <td><?= htmlspecialchars($orderItem['order_status']); ?></td>
                                    <td><?= htmlspecialchars($orderItem['payment_mode']); ?></td>
                                    <td>
                                        <a href="orders-view.php?track=<?= htmlspecialchars($orderItem['tracking_no']); ?>" class="btn btn-info mb-0 px-2 btn-sm">View</a>
                                        <a href="orders-view-print.php?track=<?= htmlspecialchars($orderItem['tracking_no']); ?>" class="btn btn-primary mb-0 px-2 btn-sm">Print</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "<h5>No Records Available</h5>"; 
                }
            } else {
                echo "<h5>Something went wrong</h5>"; 
            }
        ?>

        </div>
    </div>
</div> 

<?php include('includes/footer.php'); ?>
