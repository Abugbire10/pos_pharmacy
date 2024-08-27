<?php

include('../config/function.php');

// Initialize session variables if not set
if (!isset($_SESSION['productItems'])) {
    $_SESSION['productItems'] = [];
}

if (!isset($_SESSION['productItemIds'])) {
    $_SESSION['productItemIds'] = [];
}

// Add item to order
if (isset($_POST['addItem'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $checkProduct = mysqli_query($conn, "SELECT * FROM products WHERE id='$productId' LIMIT 1");
    if ($checkProduct) {
        if (mysqli_num_rows($checkProduct) > 0) {
            $row = mysqli_fetch_assoc($checkProduct);
            if ($row['quantity'] < $quantity) {
                redirect('order-create.php', 'Only ' . $row['quantity'] . ' quantity available!');
            }

            $productData = [
                'product_id' => $row['id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'quantity' => $quantity,
            ];

            if (!in_array($row['id'], $_SESSION['productItemIds'])) {
                array_push($_SESSION['productItemIds'], $row['id']);
                array_push($_SESSION['productItems'], $productData);
            } else {
                foreach ($_SESSION['productItems'] as $key => $prodSessionItem) {
                    if ($prodSessionItem['product_id'] == $row['id']) {
                        $newQuantity = $prodSessionItem['quantity'] + $quantity;

                        $productData = [
                            'product_id' => $row['id'],
                            'name' => $row['name'],
                            'image' => $row['image'],
                            'price' => $row['price'],
                            'quantity' => $newQuantity,
                        ];

                        $_SESSION['productItems'][$key] = $productData;
                    }
                }
            }

            redirect('order-create.php', 'Item Added: ' . $row['name']);
        } else {
            redirect('order-create.php', 'No product found!');
        }
    } else {
        redirect('order-create.php', 'Something went wrong!');
    }
}

// Update product quantity in the order
if (isset($_POST['productIncDec'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $flag = false;
    foreach ($_SESSION['productItems'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            $flag = true;
            $_SESSION['productItems'][$key]['quantity'] = $quantity;
        }
    }

    if ($flag) {
        jsonResponse(200, 'success', 'Quantity Updated');
    } else {
        jsonResponse(500, 'error', 'Something went wrong. Please refresh.');
    }
}

// Proceed to place order
if (isset($_POST['proceedToPlaceBtn'])) {
    $phone = validate($_POST['cphone']);
    $payment_mode = validate($_POST['payment_mode']);

    // Check if customer is available
    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
    if ($checkCustomer) {
        if (mysqli_num_rows($checkCustomer) > 0) {
            $customerData = mysqli_fetch_assoc($checkCustomer);

            // Set session variables
            $_SESSION['invoice_no'] = "INV-" . rand(111111, 999999);
            $_SESSION['cphone'] = $phone;
            $_SESSION['payment_mode'] = $payment_mode;

            // Save the order
            $invoice_no = validate($_SESSION['invoice_no']);
            $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];

            if (!isset($_SESSION['productItems'])) {
                jsonResponse(404, 'warning', 'No Items to place order!');
            }

            $sessionProducts = $_SESSION['productItems'];
            $totalAmount = 0;
            foreach ($sessionProducts as $amtItem) {
                $totalAmount += $amtItem['price'] * $amtItem['quantity'];
            }

            $data = [
                'customer_id' => $customerData['id'],
                'tracking_no' => rand(11111, 99999),
                'invoice_no' => $invoice_no,
                'total_amount' => $totalAmount,
                'order_date' => date('Y-m-d'),
                'order_status' => 'booked',
                'payment_mode' => $payment_mode,
                'order_placed_by_id' => $order_placed_by_id,
            ];
            $result = insert('orders', $data);
            $lastOrderId = mysqli_insert_id($conn);

            foreach ($sessionProducts as $prodItem) {
                $productId = $prodItem['product_id'];
                $price = $prodItem['price'];
                $quantity = $prodItem['quantity'];

                // Insert order items
                $dataOrderItem = [
                    'order_id' => $lastOrderId,
                    'product_id' => $productId,
                    'price' => $price,
                    'quantity' => $quantity,
                ];
                $orderItemQuery = insert('order_items', $dataOrderItem);

                // Update product quantity in the database
                $checkProductQuantityQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$productId'");
                $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
                $totalProductQuantity = $productQtyData['quantity'] - $quantity;

                $dataUpdate = [
                    'quantity' => $totalProductQuantity,
                ];
                $updateProductQty = update('products', $productId, $dataUpdate);
            }

            // Clear session data after order is placed
            unset($_SESSION['productItemIds']);
            unset($_SESSION['productItems']);
            unset($_SESSION['cphone']);
            unset($_SESSION['payment_mode']);
            unset($_SESSION['invoice_no']);

            // Redirect to order summary page
            jsonResponse(200, 'success', 'Order Placed Successfully', 'order-summary.php?order_id=' . $lastOrderId);
        } else {
            jsonResponse(404, 'warning', 'Customer Not Found!');
        }
    } else {
        jsonResponse(500, 'error', 'Something Went Wrong');
    }
}

// Save customer if not found and proceed
if (isset($_POST['saveCustomerBtn'])) {
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);

    if ($name != '' && $phone != '') {
        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
        ];
        $result = insert('customers', $data);
        if ($result) {
            jsonResponse(200, 'success', 'Customer Created Successfully');
        } else {
            jsonResponse(500, 'error', 'Something Went Wrong');
        }
    } else {
        jsonResponse(422, 'warning', 'All fields are required');
    }
}

// Save the order
if (isset($_POST['saveOrder'])) {
    $phone = validate($_SESSION['cphone']);
    $invoice_no = validate($_SESSION['invoice_no']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];

    // Check if customer exists
    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
    if (!$checkCustomer) {
        jsonResponse(500, 'error', 'Something Went Wrong!');
    }

    if (mysqli_num_rows($checkCustomer) > 0) {
        $customerData = mysqli_fetch_assoc($checkCustomer);

        if (!isset($_SESSION['productItems'])) {
            jsonResponse(404, 'warning', 'No Items to place order!');
        }

        $sessionProducts = $_SESSION['productItems'];
        $totalAmount = 0;
        foreach ($sessionProducts as $amtItem) {
            $totalAmount += $amtItem['price'] * $amtItem['quantity'];
        }

        $data = [
            'customer_id' => $customerData['id'],
            'tracking_no' => rand(11111, 99999),
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'booked',
            'payment_mode' => $payment_mode,
            'order_placed_by_id' => $order_placed_by_id,
        ];
        $result = insert('orders', $data);
        $lastOrderId = mysqli_insert_id($conn);

        foreach ($sessionProducts as $prodItem) {
            $productId = $prodItem['product_id'];
            $price = $prodItem['price'];
            $quantity = $prodItem['quantity'];

            // Insert order items
            $dataOrderItem = [
                'order_id' => $lastOrderId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
            ];
            $orderItemQuery = insert('order_items', $dataOrderItem);

            // Update product quantity in the database
            $checkProductQuantityQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$productId'");
            $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
            $totalProductQuantity = $productQtyData['quantity'] - $quantity;

            $dataUpdate = [
                'quantity' => $totalProductQuantity,
            ];
            $updateProductQty = update('products', $productId, $dataUpdate);
        }

        // Clear session data after order is placed
        unset($_SESSION['productItemIds']);
        unset($_SESSION['productItems']);
        unset($_SESSION['cphone']);
        unset($_SESSION['payment_mode']);
        unset($_SESSION['invoice_no']);

        // Redirect to order summary page
        jsonResponse(200, 'success', 'Order Placed Successfully', 'order-summary.php?order_id=' . $lastOrderId);
    } else {
        jsonResponse(404, 'warning', 'Customer Not Found!');
    }
}
?>
