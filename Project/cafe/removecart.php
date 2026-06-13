<?php

require '../config/database.php';
require '../includes/auth.php';
include '../includes/layout-start.php';

$cart_id = $_GET['id'] ?? null;
$clear = $_POST['clear'] ?? ($_GET['clear'] ?? null);

if ($cart_id) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $_SESSION['user_id']]);
} elseif ($clear) {
    // Clear complete cart for the current user
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

header('Location: cart.php');
exit;
?>


