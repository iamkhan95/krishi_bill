<?php
include("db.php");

if (!isset($_GET['id'])) {
    die("Invoice ID not provided.");
}

$invoice_id = $_GET['id'];

// Fetch invoice details
$invoiceSql = "SELECT * FROM invoices WHERE id = ?";
$stmt = $conn->prepare($invoiceSql);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();

// Fetch invoice items
$itemSql = "SELECT ii.*, p.name 
            FROM invoice_items ii
            JOIN products p ON ii.product_id = p.id
            WHERE ii.invoice_id = ?";
$itemStmt = $conn->prepare($itemSql);
$itemStmt->bind_param("i", $invoice_id);
$itemStmt->execute();
$items = $itemStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice <?php echo $invoice['invoice_number']; ?></title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f7fb;
      margin: 0;
      padding: 0;
    }
    .invoice-box {
      max-width: 900px;
      margin: 30px auto;
      padding: 30px;
      background: #fff;
      border: 2px solid #2c3e50;
      box-shadow: 0 0 20px rgba(0,0,0,0.15);
      border-radius: 12px;
    }
    .header {
      text-align: center;
      border-bottom: 3px solid #2c3e50;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }
    .header img {
      height: 70px;
      margin-bottom: 10px;
    }
    .header h2 {
      margin: 0;
      color: #2c3e50;
      font-size: 26px;
      letter-spacing: 1px;
    }
    h3 {
      color: #3498db;
      margin-top: 0;
    }
    p {
      margin: 5px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    table th, table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    table th {
      background: #2c3e50;
      color: #fff;
      text-transform: uppercase;
    }
    .total {
      text-align: right;
      font-size: 18px;
      margin-top: 20px;
      border-top: 2px solid #2c3e50;
      padding-top: 10px;
    }
    .footer {
      margin-top: 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .signature {
      text-align: left;
    }
    .signature p {
      margin-top: 60px;
      font-weight: bold;
      border-top: 1px solid #333;
      width: 200px;
    }
    .stamp {
      text-align: right;
    }
    .stamp img {
      height: 100px;
      opacity: 0.9;
    }
    .print-btn {
      display: block;
      margin: 30px auto;
      padding: 12px 25px;
      background: #27ae60;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: 0.3s;
    }
    .print-btn:hover {
      background: #219150;
    }
    @media print {
      body {
        background: none;
      }
      .print-btn {
        display: none;
      }
      .invoice-box {
        box-shadow: none;
        border: none;
      }
    }
  </style>
</head>
<body>
<div class="invoice-box">

  <div class="header">
    <img src="images/logo.jpg" alt="Logo">
    <h2>Krushi Billing System</h2>
    <p><strong>Address:</strong> Chisti Maidan, Paranda 413502 | 
       <strong>Phone:</strong> +91 9876543210</p>
  </div>

  <h3>Invoice: <?php echo $invoice['invoice_number']; ?></h3>
  <p><strong>Customer:</strong> <?php echo $invoice['customer_name']; ?><br>
     <strong>Email:</strong> <?php echo $invoice['customer_email']; ?><br>
     <strong>Phone:</strong> <?php echo $invoice['customer_phone']; ?></p>

  <table>
    <tr>
      <th>Product</th>
      <th>Price (Rs)</th>
      <th>Quantity</th>
      <th>Subtotal (Rs)</th>
    </tr>
    <?php while($row = $items->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo number_format($row['price'], 2); ?></td>
      <td><?php echo $row['quantity']; ?></td>
      <td><?php echo number_format($row['subtotal'], 2); ?></td>
    </tr>
    <?php endwhile; ?>
  </table>

  <div class="total">
    <p><strong>Total: Rs <?php echo number_format($invoice['total'], 2); ?></strong></p>
  </div>

  <div class="footer">
    <div class="signature">
      <p>Authorized Signatory</p>
    </div>
    <div class="stamp">
      <img src="images/stamp.png" alt="Company Stamp">
    </div>
  </div>

  <button onclick="window.print()" class="print-btn">🖨️ Print Invoice</button>
</div>
</body>
</html>
