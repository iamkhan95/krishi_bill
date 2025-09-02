<?php
include("db.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_name  = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];

    $product_ids = $_POST['product_id'];
    $prices      = $_POST['price'];
    $qtys        = $_POST['qty'];
    $subtotals   = $_POST['subtotal'];

    // Calculate totals
    $subTotal = array_sum($subtotals);
    $tax = $subTotal * 0.18;
    $grandTotal = $subTotal + $tax;

    // Generate unique invoice number
    $invoice_number = "INV-" . time();

    // Insert invoice record
    $sql = "INSERT INTO invoices (invoice_number, customer_name, customer_email, customer_phone, total) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssd", $invoice_number, $customer_name, $customer_email, $customer_phone, $grandTotal);

    if ($stmt->execute()) {
        $invoice_id = $stmt->insert_id;

        // Insert invoice items
        $itemSql = "INSERT INTO invoice_items (invoice_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)";
        $itemStmt = $conn->prepare($itemSql);

        for ($i = 0; $i < count($product_ids); $i++) {
            $pid = $product_ids[$i];
            $qty = $qtys[$i];
            $price = $prices[$i];
            $sub = $subtotals[$i];

            $itemStmt->bind_param("iiidd", $invoice_id, $pid, $qty, $price, $sub);
            $itemStmt->execute();
        }

        echo "<script>alert('Invoice Created Successfully!'); window.location.href='view_invoice.php?id=$invoice_id';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
