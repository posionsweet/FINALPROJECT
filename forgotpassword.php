<?php
include 'config.php';
include 'header.php';

if (isset($_POST['recover'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);

        $temp_password = substr(bin2hex(random_bytes(4)), 0, 8);
        $hashed_temp = password_hash($temp_password, PASSWORD_DEFAULT);

        $update = mysqli_query($conn, "UPDATE users SET password = '$hashed_temp' WHERE id = '{$user['id']}'");

        if ($update) {
            $mail_subject = "Thread and Trend - Password Reset";
            $mail_body = "Hi " . $user['name'] . ",\r\n\r\n"
                       . "A temporary password has been generated for your account:\r\n\r\n"
                       . $temp_password . "\r\n\r\n"
                       . "Please log in using this temporary password and change it as soon as possible.\r\n\r\n"
                       . "This is an automated message from an academic school project. Please do not reply.\r\n"
                       . "- Thread and Trend Team";
            $mail_headers = "From: no-reply@threadandtrend.local\r\n";
            @mail($email, $mail_subject, $mail_body, $mail_headers);

            $success = "Recovery successful! A temporary password has been sent to your registered email address.";
        } else {
            $error = "Password reset routine failed. Please try again.";
        }
    } else {
        $error = "No corresponding email identity records found inside current database cluster states.";
    }
}
?>

<div class="max-w-md mx-auto my-12 bg-white border border-gray-100 rounded-xl p-8 shadow-sm">
    <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <rect x="4" y="10" width="16" height="10" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke-linecap="round"/>
        </svg>
    </div>
    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight text-center mb-2">Recover Password</h2>
    <p class="text-xs text-gray-400 text-center mb-6">Enter your registered email identity map details.</p>

    <?php if (isset($error)): ?>
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 text-red-700 text-xs font-semibold rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 text-green-800 text-sm rounded leading-relaxed">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Registered Email Address</label>
            <input type="email" name="email" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
        </div>
        <button type="submit" name="recover" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase py-3 rounded tracking-wider transition shadow-sm">
            Recover Security Credentials
        </button>
    </form>
    <div class="text-center mt-6">
        <a href="login.php" class="text-xs text-indigo-600 hover:underline font-semibold">Return to Login Portal</a>
    </div>
</div>

<?php include 'footer.php'; ?>