<?php

require '../config/database.php';
require '../includes/auth.php';
include '../includes/layout-start.php';

$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);

$_SESSION['flash_success'] = 'Payment completed. Your cart is now empty.';

header('Location: /Project/dashboard.php');
exit();

?>




