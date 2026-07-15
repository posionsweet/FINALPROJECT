<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);

    $insert = mysqli_query($conn, "INSERT INTO products (name, category, price, stock, image) 
                                   VALUES ('$name', '$category', '$price', '$stock', '$image')");
    
    if ($insert) {
        $activity = "Appended a fresh inventory menu item row entry into active catalogs: Name descriptor '$name' allocated under categorization code block grouping '$category' values.";
        $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (user_id, user_name, activity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iss", $_SESSION['user_id'], $_SESSION['name'], $activity);
        mysqli_stmt_execute($stmt);

        echo "<script>alert('Product entry successfully compiled into storage schemas.'); window.location='dashboard.php';</script>";
        exit();
    } else {
        $error = "Transaction insertion routine mapping failed.";
    }
}
?>

<div class="max-w-md mx-auto bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
    <h3 class="text-xl font-extrabold text-gray-900 tracking-tight mb-6">Create Inventory Catalog Product Row</h3>
    
    <?php if (isset($error)): ?>
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 text-red-700 text-xs font-semibold rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Product Designation Title</label>
            <input type="text" name="name" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Categorization Array Grouping Mapping Block</label>
            <select name="category" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 bg-white font-medium text-gray-700">
                <option value="Shirts">Shirts Collection Menu Array</option>
                <option value="Outerwear">Outerwear Collection Menu Array</option>
                <option value="Hoodie">Hoodie Collection Menu Array</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Unit Valuation Price (PHP)</label>
                <input type="number" step="0.01" name="price" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Initial Volumetric Stock Qty</label>
                <input type="number" name="stock" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Assets Absolute Local File Storage Directory Reference Path</label>
            <input type="text" name="image" placeholder="images/imagefile.jpg" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 font-mono">
        </div>
        <div class="flex gap-2 pt-2">
            <a href="dashboard.php" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-xs uppercase py-3 rounded tracking-wider text-center transition">Cancel Transaction</a>
            <button type="submit" name="add_product" class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase py-3 rounded tracking-wider transition shadow-sm">Save Product Entry</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>