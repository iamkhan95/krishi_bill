<?php
include "config.php";
include "header.php";

// ✅ Handle Add Customer
if (isset($_POST['add_customer'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (!empty($name)) {
        $sql = "INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')";
        if ($conn->query($sql)) {
            echo "<p class='success-msg'>✅ Customer Added Successfully!</p>";
        } else {
            echo "<p class='error-msg'>❌ Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='error-msg'>⚠️ Name is required.</p>";
    }
}

// ✅ Handle Delete Customer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM customers WHERE id=$id");
    echo "<p class='success-msg'>🗑️ Customer Deleted Successfully!</p>";
}

// ✅ Handle Edit Customer
if (isset($_POST['update_customer'])) {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE customers SET name='$name', email='$email', phone='$phone' WHERE id=$id";
    if ($conn->query($sql)) {
        echo "<p class='success-msg'>✏️ Customer Updated Successfully!</p>";
    } else {
        echo "<p class='error-msg'>❌ Error: " . $conn->error . "</p>";
    }
}

// ✅ Fetch customers
$customers = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>

<h2 class="page-heading">👤 Manage Customers</h2>


<!-- Add Customer Form -->
<div class="form-container">
    <h3>Add New Customer</h3>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Customer Name" required>
        <input type="email" name="email" placeholder="Email">
        <input type="text" name="phone" placeholder="Phone">
        <button type="submit" name="add_customer" class="btn">Add Customer</button>
    </form>
</div>

<!-- Customer List -->
<h3>Customer List</h3>
<table class="customer-table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>
    <?php if ($customers->num_rows > 0): ?>
        <?php while ($row = $customers->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email'] ?: 'N/A'; ?></td>
                <td><?php echo $row['phone'] ?: 'N/A'; ?></td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn small edit-btn" 
                        onclick="openEditModal('<?php echo $row['id']; ?>','<?php echo $row['name']; ?>','<?php echo $row['email']; ?>','<?php echo $row['phone']; ?>')">✏️ Edit</button>
                    
                    <!-- Delete Button -->
                    <a href="customers.php?delete=<?php echo $row['id']; ?>" 
                       class="btn small delete-btn" 
                       onclick="return confirm('Are you sure to delete this customer?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">No customers found.</td></tr>
    <?php endif; ?>
</table>

<!-- Edit Customer Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeEditModal()">&times;</span>
    <h3>Edit Customer</h3>
    <form method="POST" action="">
        <input type="hidden" name="id" id="edit-id">
        <input type="text" name="name" id="edit-name" required>
        <input type="email" name="email" id="edit-email">
        <input type="text" name="phone" id="edit-phone">
        <button type="submit" name="update_customer" class="btn">Update Customer</button>
    </form>
  </div>
</div>

<?php include "footer.php"; ?>

<!-- JavaScript for Modal -->
<script>
function openEditModal(id, name, email, phone) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-phone').value = phone;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
