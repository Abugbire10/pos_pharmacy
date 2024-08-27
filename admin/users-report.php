<?php 
include('includes/header.php');

// Check if the session contains the necessary admin information
if (!isset($_SESSION['loggedInUser']['staff_id'])) {
    die("Error: Admin ID not found.");
}

// Get the current admin's staff ID from the session
$currentStaffId = $_SESSION['loggedInUser']['staff_id'];

// Function to get users list
function getUsers($conn) {
    $query = "SELECT user_id, name FROM users";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn) . " (Query: $query)");
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Function to get sales report for the selected user
function getSalesReport($conn, $userId, $startDate = null, $endDate = null) {
    $query = "SELECT oi.*, o.order_date as sale_date, u.name as user_name, p.name as product_name 
              FROM order_items oi
              INNER JOIN orders o ON oi.order_id = o.id
              INNER JOIN users u ON o.order_placed_by_user_id = u.user_id
              LEFT JOIN products p ON oi.product_id = p.id
              WHERE u.user_id = '$userId'";
    
    if ($startDate && $endDate) {
        $query .= " AND o.order_date BETWEEN '$startDate' AND '$endDate'";
    }
    
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn) . " (Query: $query)");
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Initialize variables
$salesReport = [];
$totalAmount = 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Fetch the sales report for the selected user
    $salesReport = getSalesReport($conn, $userId, $startDate, $endDate);
    $totalAmount = array_sum(array_map(function($item) {
        return $item['price'] * $item['quantity'];
    }, $salesReport));
}

// Fetch users list for the dropdown
$users = getUsers($conn);
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Generate Sales Report
                <a href="index.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="user_id">Select User:</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="">-- Select User --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                    <?php echo htmlspecialchars($user['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">Generate Report</button>
                    </div>
                </div>
            </form>

            <?php if (!empty($salesReport)): ?>
                <div id="report-content">
                    <h5>Sales Report</h5>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($salesReport as $sale): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sale['sale_date']); ?></td>
                                    <td><?php echo htmlspecialchars($sale['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($sale['quantity']); ?></td>
                                    <td>$<?php echo number_format($sale['price'], 2); ?></td>
                                    <td>$<?php echo number_format($sale['price'] * $sale['quantity'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Total Amount:</th>
                                <th>$<?php echo number_format($totalAmount, 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <button onclick="printReport()" class="btn btn-success">Print Report</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function printReport() {
    var reportContent = document.getElementById('report-content').innerHTML;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = reportContent;
    window.print();
    document.body.innerHTML = originalContent;
}
</script>

<?php include('includes/footer.php'); ?>
