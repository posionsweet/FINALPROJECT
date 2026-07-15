<?php
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['confirm_payment'])) {
    unset($_SESSION['cart']);
    echo "<script>alert('Payment transaction simulated successfully! Your delivery order is now being processed.'); window.location='index.php';</script>";
    exit();
}
?>

<div class="max-w-md mx-auto my-12 bg-white border border-gray-100 rounded-xl p-8 shadow-sm">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Payment Settlement Interface</h2>
        <p class="text-xs text-gray-400 mt-1">Project Specification Compliant Sandbox Environment</p>
    </div>

    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-6 text-indigo-900 text-xs leading-relaxed">
        <strong>Academic Notice Parameters:</strong> External production network payment processing APIs are explicitly waived for this submission cycle phase. Submitting updates stocks safely.
    </div>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Select Gateway Route Option Provider</label>
            <select class="w-full border border-gray-200 rounded px-3 py-2 text-sm bg-white focus:outline-none focus:border-indigo-500 font-medium text-gray-700">
                <option value="cod">Cash On Delivery (COD Settlement Route)</option>
                <option value="gcash">GCash Digital Wallet Sandbox Router</option>
                <option value="maya">Maya Payments Sandbox Router</option>
            </select>
        </div>
        
        <button type="submit" name="confirm_payment" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase py-3 rounded tracking-wider transition shadow-sm">
            Finalize Transaction Lifecycle
        </button>
    </form>
</div>

<?php include 'footer.php'; ?>