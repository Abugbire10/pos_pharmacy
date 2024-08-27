<?php include('includes/header.php'); ?>

<style>
@page {
    margin-top: 0.32in;
    margin-bottom: 0.32in;
    margin-left: 0.17in;
    margin-right: 0.17in;
}

@media print {
    body {
        margin: 0; /* Ensure the body margin doesn't interfere */
    }
    #myBillingArea {
        /* Additional styles for print, if necessary */
    }
}
</style>
<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Print Order
            <a href="orders.php" class="btn btn-danger btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="cad-body">

            <div id="myBillingArea">
                <?php
                    if(isset($_GET['track']))
                    {
                        $trackingNo = validate($_GET['track']);
                        if( $trackingNo == ''){
                            ?>
                            <div class="text-center py-5">
                                <h5>Please Provide Tracking Number</h5>
                                <div>
                                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back to Orders</a>
                                </div>
                            </div>
                        <?php
                        }

                        $orderQuery = "SELECT o.*, c.* FROM orders o, customers c 
                        WHERE c.id=o.customer_id AND tracking_no='$trackingNo' LIMIT 1";
                        
                        $orderQueryRes = mysqli_query($conn, $orderQuery);
                        if(! $orderQueryRes){
                            echo "<h5>Something went wrong</h5>";
                            return false;
                        }

                        if(mysqli_num_rows($orderQueryRes) > 0)
                        {
                            $orderDataRow = mysqli_fetch_assoc($orderQueryRes);         
                            ?>
                             <table style="width: 100%; margin-bottom: 20px;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;" colspan="2">
                                            <h4 style="font-size: 23px; line-height: 30px; margin:2px; padding: 0;">Health Habour Pharmacy</h4>
                                            <p style="font-size: 16px; line-height: 24px; margin:2px; padding: 0;">#238, Ob-Street, Twitch Avenue</p>
                                            <p style="font-size: 16px; line-height: 24px; margin:2px; padding: 0;">Health Habour Pharmacy Ltd</p>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;">Customer Details</h5>
                                                <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Name: <?= $orderDataRow['name'] ?> </p>
                                                <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Phone No: <?= $orderDataRow['phone']?> </p>
                                                <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Email Id: <?= $orderDataRow['email']?> </p>
                                            </td>

                                            <td style="text-align: right;">                        
                                                <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;">Invoice Details</h5>
                                                <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice No: <?= $orderDataRow['invoice_no']; ?> </p>
                                                <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice Date: <?= date('d M Y'); ?> </p>
                                                <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Address: 1st main road, Twitch Avenue </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                        }
                        else
                        {
                            echo "<h5>No data found!</h5>";
                            return false;
                        }

                        $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.* 
                        FROM orders o, order_items oi, products p 
                        WHERE oi.order_id=o.id AND p.id=oi.product_id
                        AND o.tracking_no='$trackingNo'";

                        $orderItemQueryRes = mysqli_query($conn, $orderItemQuery);
                        if ($orderItemQueryRes) 
                        {
                            if(mysqli_num_rows($orderItemQueryRes) > 0)
                            {
                                ?> 
                                     <div class="table-responsive mb-3">
                                <table style="width: 100%;" cellpadding="5">
                                        <thead>
                                            <tr>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="5%">ID</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" >Product Name</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="15%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $i = 1;
                                                foreach($orderItemQueryRes as $key => $row) :
                                            ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $i++; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['name']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= number_format($row['orderItemPrice'],0) ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['orderItemQuantity']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                <?= number_format($row['orderItemPrice'] * $row['orderItemQuantity'], 0) ?>
                                            </td>
                                            </tr>
                                            <?php endforeach;?>
                                            <tr>
                                                <td colspan="4" align="end" style="font-weight: bold;">Grand Total:</td>
                                                <td colspan="1" style="font-weight: bold;"><?= number_format($row['total_amount'], 0);  ?></td>
                                            </tr>
                                            <tr> 
                                                <td colspan="5">Payment Mode: <?=$row['payment_mode'];?></td>
                                            </tr>
                                            </tbody>
                                    </table>
                                </div>
                                <?php
                            }
                            else
                            {
                                echo "<h5>No data found!</h5>";
                                return false;
                            }
                        }
                        else
                        {
                            echo "<h5>Something went wrong!</h5>";
                            return false;
                        }
                    }
                    else
                    {
                        ?>
                        <div class="text-center py-5">
                            <h5>No Tracking Number Parameter Found!</h5>
                            <div>
                                <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back to Orders</a>
                            </div>
                        </div>
                    <?php
                    }
                ?>
            </div>

            <div class="mt-4 text-end">
                <button class="btn btn-info px-4 mx-1" onclick="printMyBillingArea()">Print</button>
                <button class="btn btn-primary px-4 mx-1" onclick="downloadPDF('<?= $orderDataRow['invoice_no']; ?>')">Download PDF</button>
            </div>
        </div>
    </div>
</div> 
                                                           
<?php include('includes/footer.php'); ?>

<script>
function printMyBillingArea() {
    var printContent = document.getElementById('myBillingArea').innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;

    // Reattach all JavaScript functions after resetting the body content
    window.location.reload(); // This ensures the page reloads with the original content and functionality
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function printMyBillingArea() {
    var printContent = document.getElementById('myBillingArea').innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;

    window.location.reload();
}
function downloadPDF(invoiceNo) {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({
        orientation: 'portrait',
        unit: 'in',
        format: 'letter'
    });

    const marginLeft = 0.17;
    const marginTop = 0.32;
    const content = document.getElementById('myBillingArea').innerHTML;

    // Use the pdf.html() method to add HTML content
    pdf.html(content, {
        x: marginLeft,
        y: marginTop,
        callback: function (pdf) {
            pdf.save(Invoice_${invoiceNo}.pdf);
        },
        // Optional: If you want to ensure the content fits well
        html2canvas: { scale: 2 } // Increase scale for better quality
    });
}
</script>