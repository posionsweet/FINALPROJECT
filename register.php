<?php
include 'config.php';
include 'header.php';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : 'customer';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "The input email value structure is invalid.";
    } elseif ($password !== $confirm_password) {
        $error = "The confirmation password verification matrix does not match.";
    } else {
        $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "This email identifier is already mapped to an active account.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($conn, "INSERT INTO users (name, email, password, address, contact, role) 
                                           VALUES ('$name', '$email', '$hashed_password', '$address', '$contact', '$role')");
            if ($insert) {
                $mail_subject = "Welcome to Thread and Trend - Registration Confirmation";
                $mail_body = "Hi " . $_POST['name'] . ",\r\n\r\n"
                           . "Thank you for registering an account with Thread and Trend.\r\n"
                           . "Your account has been created successfully with the email address: " . $email . "\r\n\r\n"
                           . "You may now log in and start shopping.\r\n\r\n"
                           . "This is an automated message from an academic school project. Please do not reply.\r\n"
                           . "- Thread and Trend Team";
                $mail_headers = "From: no-reply@threadandtrend.local\r\n";
                @mail($email, $mail_subject, $mail_body, $mail_headers);

                echo "<script>alert('Account creation complete! A verification confirmation notice has been sent to " . htmlspecialchars($email) . ".'); window.location='login.php';</script>";
                exit();
            } else {
                $error = "Transaction insertion routine failure. Verify inputs.";
            }
        }
    }
}
?>

<div class="max-w-lg mx-auto my-6 bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
    <div class="bg-indigo-600 px-8 py-6 text-white text-center">
        <h2 class="text-2xl font-extrabold tracking-tight">Create Customer Identity</h2>
        <p class="text-indigo-100 text-xs mt-1">Join Thread and Trend to start shopping the collection.</p>
    </div>
    <div class="p-8">

    <?php if (isset($error)): ?>
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 text-red-700 text-xs font-semibold rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Complete Name</label>
            <input type="text" name="name" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
            <input type="email" name="email" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Contact Numbers</label>
            <input type="text" name="contact" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Complete Physical Address</label>
            <textarea name="address" required class="w-full h-20 border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition resize-none"></textarea>
        </div>
        
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Administrative Privilege Assignment</label>
                <select name="role" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition bg-white">
                    <option value="customer">Customer Privilege Level</option>
                    <option value="admin">System Admin Privilege Level</option>
                </select>
            </div>
        <?php endif; ?>

        <button type="submit" name="register" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase py-3 rounded tracking-wider transition shadow-sm">
            Complete Registration Routine
        </button>
    </form>
    </div>
</div>

<?php include 'footer.php'; ?>