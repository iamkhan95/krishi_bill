<?php 
include("db.php");
include "header.php"; 

// Fetch products for dropdown
$productQuery = "SELECT * FROM products";
$productResult = $conn->query($productQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Invoice</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>


<main class="invoice-container">
  <h2>🧾 Create Invoice</h2>

  <form id="invoiceForm" method="POST" action="save_invoice.php">

    <!-- Customer Info -->
    <div class="customer-info">
      <input type="text" name="customer_name" placeholder="Customer Name" required>
      <input type="email" name="customer_email" placeholder="Customer Email">
      <input type="text" name="customer_phone" placeholder="Customer Phone">
    </div>

    <!-- Products Section -->
    <table id="invoiceTable">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price (Rs)</th>
          <th>Qty</th>
          <th>Subtotal (Rs)</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="invoiceBody">
        <tr>
          <td>
            <select name="product_id[]" class="product-select" required>
              <option value="">-- Select Product --</option>
              <?php while($row = $productResult->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" data-price="<?php echo $row['price']; ?>">
                  <?php echo $row['name']; ?>
                </option>
              <?php endwhile; ?>
            </select>
          </td>
          <td><input type="text" name="price[]" class="price" readonly></td>
          <td><input type="number" name="qty[]" class="qty" min="1" value="1"></td>
          <td><input type="text" name="subtotal[]" class="subtotal" readonly></td>
          <td><button type="button" class="remove-row">❌</button></td>
        </tr>
      </tbody>
    </table>

    <button type="button" id="addRow">➕ Add Product</button>

    <!-- Invoice Totals -->
    <div class="totals">
      <p>Subtotal: Rs <span id="subTotal">0.00</span></p>
      <p>Tax (18%): Rs <span id="tax">0.00</span></p>
      <h3>Total: Rs <span id="grandTotal">0.00</span></h3>
    </div>

    <button type="submit" class="btn">💾 Save Invoice</button>
    <button type="button" onclick="window.print()" class="btn print-btn">🖨️ Print Invoice</button>

  </form>
</main>


<script>
// Auto-fill price when selecting product
document.addEventListener("change", function(e) {
  if(e.target.classList.contains("product-select")) {
    let price = e.target.selectedOptions[0].getAttribute("data-price");
    let row = e.target.closest("tr");
    row.querySelector(".price").value = price;
    updateRow(row);
  }
});

// Update subtotal when qty changes
document.addEventListener("input", function(e) {
  if(e.target.classList.contains("qty")) {
    let row = e.target.closest("tr");
    updateRow(row);
  }
});

// Remove row
document.addEventListener("click", function(e) {
  if(e.target.classList.contains("remove-row")) {
    e.target.closest("tr").remove();
    updateTotals();
  }
});

// Add row
document.getElementById("addRow").addEventListener("click", function() {
  let body = document.getElementById("invoiceBody");
  let firstRow = body.querySelector("tr");
  let newRow = firstRow.cloneNode(true);
  newRow.querySelectorAll("input").forEach(inp => inp.value = "");
  body.appendChild(newRow);
});

// Update Row
function updateRow(row) {
  let price = parseFloat(row.querySelector(".price").value) || 0;
  let qty = parseInt(row.querySelector(".qty").value) || 1;
  let subtotal = price * qty;
  row.querySelector(".subtotal").value = subtotal.toFixed(2);
  updateTotals();
}

// Update Totals
function updateTotals() {
  let subTotal = 0;
  document.querySelectorAll(".subtotal").forEach(sub => {
    subTotal += parseFloat(sub.value) || 0;
  });
  let tax = subTotal * 0.18;
  let grandTotal = subTotal + tax;
  document.getElementById("subTotal").innerText = subTotal.toFixed(2);
  document.getElementById("tax").innerText = tax.toFixed(2);
  document.getElementById("grandTotal").innerText = grandTotal.toFixed(2);
}
</script>

<?php
include("db.php");

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete items first (foreign key relation)
    $conn->query("DELETE FROM invoice_items WHERE invoice_id = $id");
    $conn->query("DELETE FROM invoices WHERE id = $id");

    echo "<script>alert('Invoice deleted successfully!'); window.location.href='invoices.php';</script>";
}

// Fetch all invoices
$sql = "SELECT * FROM invoices ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice List</title>
  <link rel="stylesheet" href="invoices.css">
  <script>
    // 🔍 Live Search
    function searchTable() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let rows = document.querySelectorAll("#invoiceTable tbody tr");
      rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
      });
    }

    // 📑 Sort Table
    function sortTable(n) {
      let table = document.getElementById("invoiceTable");
      let rows = Array.from(table.rows).slice(1); 
      let asc = table.getAttribute("data-sort-dir") === "asc";
      rows.sort((a, b) => {
        let x = a.cells[n].innerText.toLowerCase();
        let y = b.cells[n].innerText.toLowerCase();
        return asc ? x.localeCompare(y) : y.localeCompare(x);
      });
      rows.forEach(row => table.tBodies[0].appendChild(row));
      table.setAttribute("data-sort-dir", asc ? "desc" : "asc");
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>📑 Invoice List</h2>

    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="🔍 Search invoices...">

    <table id="invoiceTable" data-sort-dir="asc">
      <thead>
        <tr>
          <th onclick="sortTable(0)">#</th>
          <th onclick="sortTable(1)">Invoice No</th>
          <th onclick="sortTable(2)">Customer</th>
          <th>Email</th>
          <th>Phone</th>
          <th onclick="sortTable(5)">Total (Rs)</th>
          <th onclick="sortTable(6)">Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['invoice_number']; ?></td>
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['customer_email']; ?></td>
            <td><?php echo $row['customer_phone']; ?></td>
            <td>Rs <?php echo number_format($row['total'], 2); ?></td>
            <td><?php echo $row['invoice_date']; ?></td>
            <td>
              <a href="view_invoice.php?id=<?php echo $row['id']; ?>" class="btn btn-view">🔍 View</a>
              <a href="invoices.php?delete=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this invoice?')">🗑 Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="8">No invoices found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
    <a href="invoices.php" class="btn-create">➕ Create New Invoice</a>
  </div>
</body>
</html>

<?php include "footer.php"; ?>

</body>
</html>
