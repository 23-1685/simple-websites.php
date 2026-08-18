<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header('Location: index.php'); exit; }
require_once '../includes/config.php';
require_once '../models/Product.php';

$productModel = new Product($pdo);

// Handle Add/Update/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $productModel->delete($_POST['id']);
    } else {
        $data = [
            'sku' => $_POST['sku'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'base_price' => $_POST['base_price'],
            'stock_quantity' => $_POST['stock_quantity'],
            'image_url' => $_POST['image_url'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        if (isset($_POST['id']) && $_POST['id'] > 0) {
            $productModel->update($_POST['id'], $data);
        } else {
            $productModel->create($data);
        }
    }
    header('Location: products.php');
    exit;
}

$products = $productModel->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Products</h1>
        <a href="index.php">Back to Dashboard</a>
        <form method="POST" class="admin-form">
            <input type="hidden" name="id" value="">
            <input type="text" name="sku" placeholder="SKU" required>
            <input type="text" name="name" placeholder="Name" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="number" name="category_id" placeholder="Category ID" required>
            <input type="number" step="0.01" name="base_price" placeholder="Price" required>
            <input type="number" name="stock_quantity" placeholder="Stock" required>
            <input type="text" name="image_url" placeholder="Image URL">
            <label><input type="checkbox" name="is_active" checked> Active</label>
            <button type="submit">Add Product</button>
        </form>
        <table class="admin-table">
            <tr><th>ID</th><th>SKU</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id']; ?></td>
                    <td><?= htmlspecialchars($p['sku']); ?></td>
                    <td><?= htmlspecialchars($p['name']); ?></td>
                    <td><?= formatPrice($p['base_price']); ?></td>
                    <td><?= $p['stock_quantity']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $p['id']; ?>">
                            <button type="submit" name="delete" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>