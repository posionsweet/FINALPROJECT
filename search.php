<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$search_query = "";
if (isset($_GET['q'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['q']);
    $results = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '%$search_query%' OR category LIKE '%$search_query%' ORDER BY name ASC");
}
?>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight mb-4 flex items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-500"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            Search Management Inventory Modules
        </h2>
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="Type target product descriptor keyword attributes here..." required class="w-full border border-gray-200 rounded px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 font-medium">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-wider px-6 py-2 rounded shadow-sm transition">Execute Search Query Filter</button>
        </form>
    </div>

    <?php if (isset($_GET['q'])): ?>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-100 text-xxs font-bold text-gray-400 uppercase tracking-widest">
                Aggregated Database Hits Matching Search Criteria String: "<?= htmlspecialchars($search_query) ?>"
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 uppercase font-bold text-xxs tracking-wider border-b border-gray-100">
                            <th class="p-4">Product Designation Details</th>
                            <th class="p-4">Category Array</th>
                            <th class="p-4 text-right">Unit Pricing Valuation</th>
                            <th class="p-4 text-center">Stock</th>
                            <th class="p-4 text-center">Actions Management</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                        <?php if (mysqli_num_rows($results) == 0): ?>
                            <tr><td colspan="5" class="p-8 text-center text-gray-400">No active inventory records map parameters match that query filtering criteria search argument string.</td></tr>
                        <?php else: ?>
                            <?php while ($prod = mysqli_fetch_assoc($results)): ?>
                                <tr class="hover:bg-gray-50/25">
                                    <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($prod['name']) ?></td>
                                    <td class="p-4"><span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full font-bold uppercase tracking-wide"><?= htmlspecialchars($prod['category']) ?></span></td>
                                    <td class="p-4 text-right font-mono font-bold text-indigo-600">PHP <?= number_format($prod['price'], 2) ?></td>
                                    <td class="p-4 text-center font-bold font-mono text-gray-700"><?= htmlspecialchars($prod['stock']) ?></td>
                                    <td class="p-4 text-center space-x-3">
                                        <a href="edit.php?id=<?= $prod['id'] ?>" class="text-xs text-amber-600 font-bold hover:underline">Edit specifications</a>
                                        <a href="delete.php?id=<?= $prod['id'] ?>" onclick="return confirm('Execute permanent removal transaction drop on this database row record identity map?')" class="text-xs text-red-500 font-bold hover:underline">Delete row entry</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>