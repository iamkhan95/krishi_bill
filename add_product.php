<?php include 'db.php'; ?>
<?php include 'header.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $desc  = $_POST['description'];

    // Insert product
    $insert = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
    $insert->bind_param("sds", $name, $price, $desc);

    if ($insert->execute()) {
        $productId = $insert->insert_id;

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "assets/products/";
            $targetFile = $targetDir . $productId . ".jpg";
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        }

        echo "<script>alert('✅ Product added successfully!'); window.location='products.php';</script>";
    } else {
        echo "<p style='color:red;'>❌ Error adding product.</p>";
    }
}
?>

<div class="form-container">
    <h2 class="page-heading">➕ Add New Product</h2>

    <form method="POST" enctype="multipart/form-data" class="product-form">
        <div class="form-group">
            <label>Product Name:</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Price (₹):</label>
            <input type="number" step="0.01" name="price" required>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label>Upload Image:</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn save">💾 Save Product</button>
            <a href="products.php" class="btn cancel">❌ Cancel</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
