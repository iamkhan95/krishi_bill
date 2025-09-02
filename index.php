<?php
include "db.php";
include "header.php";


// ✅ Fetch Recent Products
$productQuery = "SELECT * FROM products ORDER BY created_at DESC LIMIT 5";
$productResult = $conn->query($productQuery);

// ✅ Fetch Star Customers
$starQuery = "SELECT * FROM customers ORDER BY id DESC LIMIT 5";
$starResult = $conn->query($starQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Billing System Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<header>
  <h1>📊 Billing System Dashboard</h1>
</header>

<main>

  <!-- Quick Actions -->
  <div class="actions">
    <a href="invoices.php" class="btn">🧾 Create Invoice</a>
    <a href="customers.php" class="btn">👤 Add Customer</a>
    <a href="products.php" class="btn">📦 Add Product</a>
  </div>

  <section class="section recent-invoices">
  <div class="section-header fancy-header">
    <div class="logo-wrapper">
      <img src="images/logo.jpg" alt="Vitthal Krushi Seva Kendra Logo" class="logo">
      <span class="glow-circle"></span>
    </div>
    <h2>✨ Vitthal Krushi Seva Kendra – All Invoices ✨</h2>
  </div>
</section>




  <!-- Recent Products -->
  <section class="section">
    <h2>📦 Recently Added Products</h2>
    <div class="cards">
      <?php if ($productResult && $productResult->num_rows > 0): ?>
        <?php while($prod = $productResult->fetch_assoc()): ?>
          <div class="card">
            <h3><?php echo $prod['name']; ?></h3>
            <p><strong>Price:</strong> Rs <?php echo number_format($prod['price'], 2); ?></p>
            <p><strong>Stock:</strong> <?php echo $prod['stock']; ?></p>
            <p><small>📅 <?php echo $prod['created_at']; ?></small></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No products yet.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Star Customers -->
  <section class="section">
    <h2>🌟 Star Customers</h2>
    <div class="cards">
      <?php if ($starResult && $starResult->num_rows > 0): ?>
        <?php while($cust = $starResult->fetch_assoc()): ?>
          <div class="card">
            <h3><?php echo $cust['name']; ?></h3>
            <p>📧 <?php echo $cust['email'] ?: 'No Email'; ?></p>
            <p>📞 <?php echo $cust['phone'] ?: 'No Phone'; ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No customers yet.</p>
      <?php endif; ?>
    </div>
  </section>

</main>

<?php include "footer.php"; ?>


</body>
</html>
