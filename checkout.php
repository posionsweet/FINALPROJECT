<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($user_query);

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
$cart_products = [];

if (!empty($cart)) {
    $product_ids = implode(',', array_map('intval', array_keys($cart)));
    $products_query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($product_ids)");
    while ($row = mysqli_fetch_assoc($products_query)) {
        $row['qty'] = $cart[$row['id']];
        $row['total_price'] = $row['price'] * $row['qty'];
        $subtotal += $row['total_price'];
        $cart_products[] = $row;
    }
}

if (isset($_POST['place_order'])) {
    if (empty($cart_products)) {
        $error_msg = "Your inventory basket array is currently empty.";
    } else {
        $stock_ok = true;
        foreach ($cart_products as $item) {
            if ($item['stock'] < $item['qty']) {
                $stock_ok = false;
                $error_msg = "Insufficient remaining quantities configuration allocation for: " . htmlspecialchars($item['name']);
                break;
            }
        }

        if ($stock_ok) {
            mysqli_begin_transaction($conn);
            try {
                foreach ($cart_products as $item) {
                    $new_stock = $item['stock'] - $item['qty'];
                    mysqli_query($conn, "UPDATE products SET stock = '$new_stock' WHERE id = '{$item['id']}'");
                }

                $activity = "Placed transaction order totaling a volume aggregate of PHP " . number_format($subtotal, 2);
                $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (user_id, user_name, activity) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "iss", $user_id, $user['name'], $activity);
                mysqli_stmt_execute($stmt);

                mysqli_commit($conn);
                header("Location: payment.php");
                exit();
            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error_msg = "Database internal atomic isolation routine transaction fail.";
            }
        }
    }
}
?>

<div class="max-w-6xl mx-auto py-4">
    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-8">Checkout Transaction Process</h2>

    <?php if (isset($error_msg)): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 text-sm font-semibold">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <form method="POST" class="lg:col-span-7 bg-white border border-gray-100 rounded-xl p-6 shadow-sm space-y-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Shipping Allocation Parameters</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Recipient Identity Name</label>
                        <input type="text" value="<?= htmlspecialchars($user['name']) ?>" disabled class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 text-sm text-gray-400 cursor-not-allowed font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Destination Physical Address Coordinates</label>
                        <textarea disabled class="w-full h-24 bg-gray-50 border border-gray-200 rounded px-3 py-2 text-sm text-gray-400 cursor-not-allowed resize-none font-medium"><?= htmlspecialchars($user['address']) ?></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email Verification Node</label>
                            <input type="text" value="<?= htmlspecialchars($user['email']) ?>" disabled class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 text-sm text-gray-400 cursor-not-allowed font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Contact Handset Line Connection</label>
                            <input type="text" value="<?= htmlspecialchars($user['contact']) ?>" disabled class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 text-sm text-gray-400 cursor-not-allowed font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" name="place_order" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm uppercase py-3 rounded-lg shadow transition tracking-wider">
                Confirm Allocation Order Setup
            </button>
        </form>

        <div class="lg:col-span-5 bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Items Review Pipeline</h3>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto pr-2">
                <?php foreach ($cart_products as $item): ?>
                    <div class="py-4 flex gap-4">
                        <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0 p-1">
                            <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="h-full w-full object-contain">
                            <?php else: ?>
                                <div class="text-gray-300 font-bold text-xxs tracking-wider uppercase">Apparel</div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate"><?= htmlspecialchars($item['name']) ?></h4>
                            <p class="text-xs text-gray-400 mt-1">Quantity Batch: <?= htmlspecialchars($item['qty']) ?></p>
                        </div>
                        <div class="text-right flex-shrink-0 font-bold text-gray-800 text-sm">
                            PHP <?= number_format($item['total_price'], 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="border-t border-gray-100 pt-4 mt-4 space-y-2">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Aggregate Subtotal</span>
                    <span>PHP <?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-gray-900 pt-2 border-t border-gray-100">
                    <span>Total Valuation Amount Due</span>
                    <span class="text-indigo-600">PHP <?= number_format($subtotal, 2) ?></span>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>