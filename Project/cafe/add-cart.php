<?php

require '../config/database.php';
require '../includes/auth.php';
require '../includes/layout-start.php';

$item_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];
$quantity = (int)($_GET['quantity'] ?? 1);

if ($quantity < 1) {
    $quantity = 1;
}

if ($item_id) {
    // Fetch correct item data from cafe_items
    $stmt = $pdo->prepare("SELECT name, price FROM cafe_items WHERE id = ?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        $_SESSION['flash_error'] = 'Invalid item.';
        header('Location: menu.php');
        exit;
    }

    $item_name = $item['name'];
    $price = $item['price'];

    // Check if the item is already in the cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$user_id, $item_id]);
    $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // Increment quantity (bugfix: $action was undefined)
        $new_quantity = ((int)$existing_item['quantity'] + $quantity);
        $stmt = $pdo->prepare(
            "UPDATE cart SET quantity = ?, item_name = ?, price = ? WHERE id = ?"
        );
        $stmt->execute([$new_quantity, $item_name, $price, $existing_item['id']]);
    } else {
        // If the item is not in the cart, insert a new record
        $stmt = $pdo->prepare(
            "INSERT INTO cart (user_id, item_id, item_name, price, quantity) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$user_id, $item_id, $item_name, $price, $quantity]);
    }

    // Flash success message for menu.php
    $_SESSION['flash_success'] = "$item_name added to your cart.";

    // Redirect to the menu page after adding the item
    header('Location: menu.php');
    exit;
}

$_SESSION['flash_error'] = 'Invalid item ID.';
header('Location: menu.php');
exit;


