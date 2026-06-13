<?php

require '../config/database.php';
require '../includes/auth.php';
require '../includes/layout-start.php';

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ? and user_id = ? ");
$stmt->execute([$id, $_SESSION['user_id']]);
header('Location: bookings.php');
exit();

include '../includes/layout-end.php'

?>


