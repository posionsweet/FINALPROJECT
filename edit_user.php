<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");

if (mysqli_num_rows($user_query) !== 1) {
    header("Location: manage_users.php");
    exit();
}

$target = mysqli_fetch_assoc($user_query);

if (isset($_POST['update_user'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $role = $_POST['role'] === 'admin' ? 'admin' : 'customer';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "The input email value structure is invalid.";
    } else {
        $update = mysqli_query($conn, "UPDATE users SET name='$name', email='$email', address='$address', contact='$contact', role='$role' WHERE id='$id'");

        if ($update) {
            $activity = "Modified user account record for '$name' (ID: $id). Role set to '$role'.";
            $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (user_id, user_name, activity) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iss", $_SESSION['user_id'], $_SESSION['name'], $activity);
            mysqli_stmt_execute($stmt);

            echo "<script>alert('User account updated successfully.'); window.location='manage_users.php';</script>";
            exit();
        } else {
            $error = "Account update operation failed.";
        }
    }
}
?>

<div class="max-w-md mx-auto bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
    <h3 class="text-xl font-extrabold text-gray-900 tracking-tight mb-6">Modify User Account</h3>

    <?php if (isset($error)): ?>
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 text-red-700 text-xs font-semibold rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($target['id'] == $_SESSION['user_id']): ?>
        <div class="mb-4 bg-amber-50 border-l-4 border-amber-500 p-3 text-amber-700 text-xs font-semibold rounded">
            Note: You are editing your own account. Changing your role away from admin will remove your own admin access.
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Complete Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($target['name']) ?>" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($target['email']) ?>" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Contact Number</label>
            <input type="text" name="contact" value="<?= htmlspecialchars($target['contact']) ?>" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Complete Address</label>
            <textarea name="address" required class="w-full h-20 border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 resize-none"><?= htmlspecialchars($target['address']) ?></textarea>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Account Role</label>
            <select name="role" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 bg-white">
                <option value="customer" <?= $target['role'] === 'customer' ? 'selected' : '' ?>>Customer Privilege Level</option>
                <option value="admin" <?= $target['role'] === 'admin' ? 'selected' : '' ?>>System Admin Privilege Level</option>
            </select>
        </div>
        <div class="flex gap-2 pt-2">
            <a href="manage_users.php" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-xs uppercase py-3 rounded tracking-wider text-center transition">Cancel</a>
            <button type="submit" name="update_user" class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase py-3 rounded tracking-wider transition shadow-sm">Save Changes</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
