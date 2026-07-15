<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $fetch = mysqli_query($conn, "SELECT name FROM products WHERE id = '$id'");
    if (mysqli_num_rows($fetch) === 1) {
        $prod = mysqli_fetch_assoc($fetch);
        $prod_name = $prod['name'];

        $delete = mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");
        
        if ($delete) {
            $activity = "Permanently dropped and purged inventory row entry index reference mapping key value $id ('$prod_name') from product tables rows configuration.";
            $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (user_id, user_name, activity) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iss", $_SESSION['user_id'], $_SESSION['name'], $activity);
            mysqli_stmt_execute($stmt);
        }
    }
}

header("Location: dashboard.php");
exit();
?>