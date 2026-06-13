<?php

require '../config/database.php';
require '../includes/auth.php';
require '../includes/layout-start.php';

$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
include '../includes/navbar.php';
?>

<div class="max-w-6xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">My Cart</h1>

    <?php if (count($cart_items) > 0): ?>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">Item</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo htmlspecialchars($item['item_name']); ?></td>
                        <td class="px-4 py-2">Rs. <?php echo number_format($item['price'], 2); ?></td>
                        <td class="px-4 py-2"><?php echo (int)$item['quantity']; ?></td>
                        <td class="px-4 py-2">Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td class="px-4 py-2">
                            <a href="./editcart.php?id=<?php echo (int)$item['id']; ?>" class="text-blue-500 hover:underline">Edit</a>
                            <a href="./removecart.php?id=<?php echo (int)$item['id']; ?>" class="text-red-500 hover:underline">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Clear cart (complete) -->
        <form method="POST" action="./removecart.php" class="mt-6">
            <input type="hidden" name="clear" value="1">
            <button
                type="submit"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                onclick="return confirm('Are you sure you want to clear the complete cart?')"
            >
                Clear Cart
            </button>
        </form>

        <!-- Add checkout button -->
        <div class="mt-3">
            <a href="proceedtocheckout.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Proceed to Checkout</a>
        </div>

    <?php else: ?>
        <p class="text-gray-600">Your cart is empty. <a href="menu.php" class="text-blue-500 hover:underline">Browse the menu</a> to add items to your cart.</p><br>
        <a href="../dashboard.php" class="text-blue-500 hover:underline">Return to Dashboard</a>
    <?php endif; ?>

</div>

<?php
include '../includes/layout-end.php';
?>

