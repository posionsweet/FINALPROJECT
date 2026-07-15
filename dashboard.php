<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$inventory_query = mysqli_query($conn, "SELECT * FROM products ORDER BY stock ASC");
$logs_query = mysqli_query($conn, "SELECT * FROM audit_logs ORDER BY logged_at DESC LIMIT 50");

$total_products = mysqli_num_rows($inventory_query);
$low_stock_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products WHERE stock <= 5");
$low_stock_count = mysqli_fetch_assoc($low_stock_result)['total'];
$total_logs_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM audit_logs");
$total_logs = mysqli_fetch_assoc($total_logs_result)['total'];
?>

<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-6 rounded-xl border border-gray-100 shadow-sm gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Seller Management Workspace</h2>
            <p class="text-xs text-gray-400 mt-0.5">Configure system admin credentials, manage product entries, and evaluate track audit trails.</p>
        </div>
        <div class="flex gap-2">
            <a href="add.php" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded shadow-sm">Add New Product</a>
            <a href="manage_users.php" class="bg-gray-800 hover:bg-gray-900 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded shadow-sm">Manage Users</a>
            <a href="register.php" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded shadow-sm">Create Account</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xxs font-bold uppercase tracking-widest text-gray-400">Total Products</p>
            <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= $total_products ?></p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xxs font-bold uppercase tracking-widest text-gray-400">Low Stock Items (&le;5)</p>
            <p class="text-3xl font-extrabold <?= $low_stock_count > 0 ? 'text-red-600' : 'text-gray-900' ?> mt-1"><?= $low_stock_count ?></p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xxs font-bold uppercase tracking-widest text-gray-400">Logged Activities</p>
            <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= $total_logs ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wider text-gray-500 flex items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-500"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Inventory Status Stock Levels Report
            </h3>
            <a href="search.php" class="text-xs text-indigo-600 hover:underline font-bold">Search Product Entries</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase font-bold text-xxs tracking-wider border-b border-gray-100">
                        <th class="p-4">Product Descriptor Details</th>
                        <th class="p-4">Category Assignment</th>
                        <th class="p-4 text-right">Unit Pricing Valuation</th>
                        <th class="p-4 text-center">Remaining Stock Metrics</th>
                        <th class="p-4 text-center">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    <?php if (mysqli_num_rows($inventory_query) == 0): ?>
                        <tr><td colspan="5" class="p-8 text-center text-gray-400">No configured items located inside database tables rows.</td></tr>
                    <?php endif; ?>
                    <?php while ($prod = mysqli_fetch_assoc($inventory_query)): ?>
                        <tr class="hover:bg-gray-50/25">
                            <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($prod['name']) ?></td>
                            <td class="p-4"><span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full font-bold uppercase tracking-wide"><?= htmlspecialchars($prod['category']) ?></span></td>
                            <td class="p-4 text-right font-mono font-bold text-indigo-600">PHP <?= number_format($prod['price'], 2) ?></td>
                            <td class="p-4 text-center">
                                <span class="font-bold font-mono <?= $prod['stock'] <= 5 ? 'text-red-600 bg-red-50 px-2 py-0.5 rounded' : 'text-gray-700' ?>">
                                    <?= htmlspecialchars($prod['stock']) ?>
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-3">
                                <a href="edit.php?id=<?= $prod['id'] ?>" class="text-xs text-amber-600 font-bold hover:underline">Modify specifications</a>
                                <a href="delete.php?id=<?= $prod['id'] ?>" onclick="return confirm('Execute permanent removal transaction drop on this database row record identity map?')" class="text-xs text-red-500 font-bold hover:underline">Delete row entry</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wider text-gray-500 flex items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-500"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/></svg>
                Platform Events Audit Log Tracker Log File
            </h3>
        </div>
        <div class="overflow-x-auto max-h-96">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase font-bold text-xxs tracking-wider border-b border-gray-100">
                        <th class="p-4">System Logging Chronological Timestamp</th>
                        <th class="p-4">Account Profile Node Source</th>
                        <th class="p-4">Action Performance Activity Evaluation Vector</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-mono text-xs text-gray-500">
                    <?php if (mysqli_num_rows($logs_query) == 0): ?>
                        <tr><td colspan="3" class="p-8 text-center text-gray-400">No administrative events serialized inside audit trails storage tables files.</td></tr>
                    <?php endif; ?>
                    <?php while ($log = mysqli_fetch_assoc($logs_query)): ?>
                        <tr class="hover:bg-gray-50/25">
                            <td class="p-4 text-gray-400 whitespace-nowrap"><?= $log['logged_at'] ?></td>
                            <td class="p-4 font-bold text-gray-700 whitespace-nowrap"><?= htmlspecialchars($log['user_name']) ?> (Profile Reference Node ID Key: <?= $log['user_id'] ?>)</td>
                            <td class="p-4 text-gray-600 font-sans leading-relaxed"><?= htmlspecialchars($log['activity']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>