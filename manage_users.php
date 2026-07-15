<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$users_query = mysqli_query($conn, "SELECT id, name, email, address, contact, role, created_at FROM users ORDER BY created_at DESC");
?>

<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-6 rounded-xl border border-gray-100 shadow-sm gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">User Account Management</h2>
            <p class="text-xs text-gray-400 mt-0.5">View all registered accounts and modify user roles and details.</p>
        </div>
        <a href="register.php" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded shadow-sm">Create New Account</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wider text-gray-500">All Registered Accounts</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase font-bold text-xxs tracking-wider border-b border-gray-100">
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Contact</th>
                        <th class="p-4 text-center">Role</th>
                        <th class="p-4 text-center">Joined</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    <?php if (mysqli_num_rows($users_query) == 0): ?>
                        <tr><td colspan="6" class="p-8 text-center text-gray-400">No user accounts found.</td></tr>
                    <?php endif; ?>
                    <?php while ($u = mysqli_fetch_assoc($users_query)): ?>
                        <tr class="hover:bg-gray-50/25">
                            <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($u['name']) ?></td>
                            <td class="p-4"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="p-4"><?= htmlspecialchars($u['contact']) ?></td>
                            <td class="p-4 text-center">
                                <span class="text-xs px-2 py-0.5 rounded-full font-bold uppercase tracking-wide <?= $u['role'] === 'admin' ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-100 text-gray-600' ?>">
                                    <?= htmlspecialchars($u['role']) ?>
                                </span>
                            </td>
                            <td class="p-4 text-center text-xs text-gray-400"><?= htmlspecialchars($u['created_at']) ?></td>
                            <td class="p-4 text-center">
                                <a href="edit_user.php?id=<?= $u['id'] ?>" class="text-xs text-amber-600 font-bold hover:underline">Modify Account</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
