<?php
include 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_num_rows($query) === 1 ? mysqli_fetch_assoc($query) : null;

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        $activity = "Successful portal login authorization verified.";
        $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (user_id, user_name, activity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iss", $user['id'], $user['name'], $activity);
        mysqli_stmt_execute($stmt);

        if ($user['role'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "The credentials match configuration failed.";
    }
}

include 'header.php';
?>

<div class="max-w-4xl mx-auto my-8 bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-2">

    <div class="hidden md:flex flex-col justify-center bg-indigo-600 p-10 text-white">
        <svg width="40" height="40" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-6">
            <rect width="28" height="28" rx="7" fill="white" fill-opacity="0.15"/>
            <path d="M14 6L20 9.5V17L14 20.5L8 17V9.5L14 6Z" stroke="white" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M14 6V20.5" stroke="white" stroke-width="1.6"/>
        </svg>
        <h3 class="text-2xl font-black tracking-tight">Welcome Back</h3>
        <p class="text-indigo-100 text-sm mt-2 leading-relaxed">Sign in to track your orders, manage your cart, and continue shopping the collection.</p>
    </div>

    <div class="p-8">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-6">Account Login</h2>

        <?php if (isset($error)): ?>
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 text-red-700 text-xs font-semibold rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition">
            </div>
            <div class="text-right">
                <a href="forgotpassword.php" class="text-xs text-indigo-600 hover:underline">Forgot Password?</a>
            </div>
            <button type="submit" name="login" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase py-3 rounded tracking-wider transition shadow-sm">
                Sign In
            </button>
        </form>
        <p class="text-center text-xs text-gray-400 mt-6">
            Don't have an identity profile? <a href="register.php" class="text-indigo-600 font-bold hover:underline">Register Here</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>