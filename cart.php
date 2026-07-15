<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $stock_check = mysqli_query($conn, "SELECT stock FROM products WHERE id = '$product_id'");
    $stock_row = mysqli_fetch_assoc($stock_check);
    $available_stock = $stock_row ? intval($stock_row['stock']) : 0;

    $current_qty = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
    if ($current_qty < $available_stock) {
        $_SESSION['cart'][$product_id] = $current_qty + 1;
    }
    header("Location: cart.php");
    exit();
}

if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $product_id => $qty) {
        $product_id = intval($product_id);
        $qty = intval($qty);

        $stock_check = mysqli_query($conn, "SELECT stock FROM products WHERE id = '$product_id'");
        $stock_row = mysqli_fetch_assoc($stock_check);
        $available_stock = $stock_row ? intval($stock_row['stock']) : 0;

        if ($qty <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = min($qty, $available_stock);
        }
    }
    header("Location: cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

$cart_products = [];
$subtotal = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
    while ($row = mysqli_fetch_assoc($query)) {
        $row['qty'] = $_SESSION['cart'][$row['id']];
        $row['total_price'] = $row['price'] * $row['qty'];
        $subtotal += $row['total_price'];
        $cart_products[] = $row;
    }
}
?>

<div class="max-w-4xl mx-auto py-4">
    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-8">Shopping Cart Status</h2>

    <?php if (empty($cart_products)): ?>
        <div class="text-center py-16 bg-white border border-gray-100 rounded-xl shadow-sm">
            <svg class="mx-auto mb-4 text-gray-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="9" cy="20" r="1.2" fill="currentColor" stroke="none"/>
                <circle cx="17" cy="20" r="1.2" fill="currentColor" stroke="none"/>
                <path d="M3 4h2l2.4 11.4a2 2 0 0 0 2 1.6h7.2a2 2 0 0 0 2-1.6L20 8H6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-700">Your shopping cart array is currently empty</h3>
            <p class="text-gray-400 text-sm mt-2">Add apparel catalog items from our main navigation store routes to process checkout.</p>
            <a href="index.php" class="inline-block mt-6 bg-indigo-600 text-white text-xs px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-bold uppercase tracking-wider">Explore Catalog Collection</a>
        </div>
    <?php else: ?>
        <form method="POST" action="cart.php" class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 divide-y divide-gray-100">
                <?php foreach ($cart_products as $item): ?>
                    <div class="py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex gap-4 items-center">
                            <div class="w-20 h-20 bg-gray-50 border border-gray-100 rounded-lg overflow-hidden flex items-center justify-center p-1 flex-shrink-0">
                                <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="h-full w-full object-contain">
                                <?php else: ?>
                                    <div class="text-gray-300 font-bold text-xxs tracking-wider uppercase">Item</div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-base"><?= htmlspecialchars($item['name']) ?></h4>
                                <span class="text-xs text-indigo-600 font-bold block mt-1">PHP <?= number_format($item['price'], 2) ?> Each</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-6">
                            <div class="flex items-center border border-gray-200 rounded">
                                <input type="number" name="qty[<?= $item['id'] ?>]" value="<?= $item['qty'] ?>" min="1" max="<?= $item['stock'] ?>" class="w-16 text-center text-sm py-1 focus:outline-none font-semibold text-gray-700">
                            </div>
                            <div class="text-right min-w-[100px]">
                                <span class="font-extrabold text-gray-800 text-sm block">PHP <?= number_format($item['total_price'], 2) ?></span>
                                <a href="cart.php?remove=<?= $item['id'] ?>" class="text-xs text-red-500 hover:underline mt-1 inline-block">Remove Item</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-gray-50 p-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <button type="submit" name="update_cart" class="text-xs bg-white border border-gray-200 text-gray-600 font-bold uppercase tracking-wider px-4 py-2.5 rounded shadow-sm hover:bg-gray-50 transition">
                    Update Quantity Inputs
                </button>
                <div class="text-center sm:text-right">
                    <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Cart Matrix Total Subtotal</span>
                    <span class="block text-2xl font-extrabold text-indigo-600 mt-0.5">PHP <?= number_format($subtotal, 2) ?></span>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                <a href="index.php" class="text-xs bg-gray-100 text-gray-600 font-bold uppercase tracking-wider px-6 py-3 rounded-lg text-center hover:bg-gray-200 transition">Return to Shop</a>
                <a href="checkout.php" class="text-xs bg-indigo-600 text-white font-bold uppercase tracking-wider px-8 py-3 rounded-lg text-center hover:bg-indigo-700 transition shadow-sm">Proceed to Shipping Checkout</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>