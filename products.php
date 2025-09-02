<?php
include __DIR__ . '/db.php';
include "header.php"; 

// Add product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name  = $conn->real_escape_string($_POST['name']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];

    $sql = "INSERT INTO products (name, price, stock) VALUES ('$name', '$price', '$stock')";
    if ($conn->query($sql)) {
        $success = "✅ Product added successfully!";
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}

// Delete product
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: products.php?msg=deleted");
    exit;
}

// Update product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $id    = (int) $_POST['id'];
    $name  = $conn->real_escape_string($_POST['name']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];

    $sql = "UPDATE products SET name='$name', price='$price', stock='$stock' WHERE id=$id";
    if ($conn->query($sql)) {
        $success = "✅ Product updated successfully!";
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}

// Fetch products
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <h1 class="page-heading">🛒 Manage Products</h1>

        <!-- Alerts -->
        <?php if (isset($success)) echo "<p class='alert success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='alert error'>$error</p>"; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') echo "<p class='alert success'>🗑️ Product deleted successfully!</p>"; ?>

        <!-- Add Product Form -->
        <div class="card form-card">
            <h2>Add New Product</h2>
            <form method="POST">
                <label>Product Name</label>
                <input type="text" name="name" required>

                <label>Price (Rs)</label>
                <input type="number" step="0.01" name="price" required>

                <label>Stock Quantity</label>
                <input type="number" name="stock" required>

                <button type="submit" name="add_product">➕ Add Product</button>
            </form>
        </div>

        <!-- Products List -->
        <div class="card table-card">
            <h2>All Products</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Price (Rs)</th>
                        <th>Stock</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>Rs<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['stock']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn edit-btn" onclick="openEditForm(<?php echo $row['id']; ?>, '<?php echo $row['name']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['stock']; ?>')">✏️ Edit</button>
                                
                                <!-- Delete Button -->
                                <a class="btn delete-btn" href="products.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure to delete this product?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="empty">No products found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <span class="close" onclick="closeEditForm()">&times;</span>
            <h2>Edit Product</h2>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <label>Product Name</label>
                <input type="text" name="name" id="edit_name" required>

                <label>Price (Rs)</label>
                <input type="number" step="0.01" name="price" id="edit_price" required>

                <label>Stock Quantity</label>
                <input type="number" name="stock" id="edit_stock" required>

                <button type="submit" name="update_product">💾 Update Product</button>
            </form>
        </div>
    </div>

   <?php include "footer.php"; ?>

   
    <script>
        function openEditForm(id, name, price, stock) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('editModal').style.display = "block";
        }
        function closeEditForm() {
            document.getElementById('editModal').style.display = "none";
        }
    </script>
</body>
</html>
