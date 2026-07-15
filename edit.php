<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");

if (mysqli_num_rows($product_query) !== 1) {
    header("Location: dashboard.php");
    exit();
}

$product = mysqli_fetch_assoc($product_query);

if (isset($_POST['update_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);

    $update = mysqli_query($conn, "UPDATE products SET name='$name', category='$category', price='$price', stock='$stock', image='$image' WHERE id='$id'");
    
    if ($update) {
        $activity = "Modified target product master row metadata values for index key $id. Updated target product identity name description map to '$name', cost vector matrix to PHP $price, stock balances matrix value array onto: $stock.";
        $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (user_id, user_name, activity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iss", $_SESSION['user_id'], $_SESSION['name'], $activity);
        mysqli_stmt_execute($stmt);

        echo "<script>alert('Specifications update modification commit successful.'); window.location='dashboard.php';</script>";
        exit();
    } else {
        $error = "Transaction specification update operation failed execution routines.";
    }
}
?>

<div class="max-w-md mx-auto bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
    <h3 class="text-xl font-extrabold text-gray-900 tracking-tight mb-6">Modify Stock System Record Metrics</h3>

    <?php if (isset($error)): ?>
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 text-red-700 text-xs font-semibold rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Product Title Description Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Category Classification Matrix</label>
            <select name="category" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 bg-white font-medium text-gray-700">
                <option value="Shirts" <?= $product['category'] === 'Shirts' ? 'selected' : '' ?>>Shirts Collection Menu Array</option>
                <option value="Outerwear" <?= $product['category'] === 'Outerwear' ? 'selected' : '' ?>>Outerwear Collection Menu Array</option>
                <option value="Hoodie" <?= $product['category'] === 'Hoodie' ? 'selected' : '' ?>>Hoodie Collection Menu Array</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Unit Value Pricing (PHP)</label>
                <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Current Warehouse Volume Stock Balance</label>
                <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Asset Target Absolute File Storage Directory Directory Reference Path</label>
            <input type="text" name="image" value="<?= htmlspecialchars($product['image']) ?>" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 font-mono">
        </div>
        <div class="flex gap-2 pt-2">
            <a href="dashboard.php" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-xs uppercase py-3 rounded tracking-wider text-center transition">Cancel Changes</a>
            <button type="submit" name="update_product" class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase py-3 rounded tracking-wider transition shadow-sm">Commit Changes Parameters</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>