<?php

require '../config/database.php';
require '../includes/auth.php';
require '../includes/layout-start.php';

// Load cart items from DB (same as cafe/cart.php)
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Totals
$subtotal = 0;
foreach ($cart_items as $it) {
    $subtotal += (float)$it['price'] * (int)$it['quantity'];
}

$total = $subtotal;

include '../includes/navbar.php';
?>

<div class="max-w-6xl mx-auto p-6">

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <script>
            alert(<?= json_encode($_SESSION['flash_success']) ?>);
        </script>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <script>
            alert(<?= json_encode($_SESSION['flash_error']) ?>);
        </script>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div class="flex items-start justify-between gap-6 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Invoice / Bill</h1>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-600">Invoice No</div>
            <div class="font-semibold">#<?= htmlspecialchars(date('YmdHis')) ?></div>
            <div class="text-sm text-gray-600 mt-2">Date</div>
            <div class="font-semibold"><?= htmlspecialchars(date('Y-m-d')) ?></div>
        </div>
    </div>

    <?php if (count($cart_items) > 0): ?>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr class="text-left text-sm text-gray-700">
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <?php
                            $qty = (int)$item['quantity'];
                            $price = (float)$item['price'];
                            $lineTotal = $price * $qty;
                        ?>
                        <tr class="border-t">
                            <td class="px-4 py-3"><?= htmlspecialchars($item['item_name']) ?></td>
                            <td class="px-4 py-3">Rs. <?= number_format($price, 2) ?></td>
                            <td class="px-4 py-3"><?= $qty ?></td>
                            <td class="px-4 py-3">Rs. <?= number_format($lineTotal, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mt-6">
            <div class="md:col-span-2">
                <a href="cart.php" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Back to Cart
                </a>
                <p class="text-xs text-gray-500 mt-2">Review items before placing order.</p>
            </div>

            <div class="bg-white shadow-md rounded-lg p-5">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">Rs. <?= number_format($subtotal, 2) ?></span>
                </div>
                
                <div class="border-t mt-3 pt-3 flex justify-between">
                    <span class="font-semibold">Total</span>
                    <span class="text-lg font-bold">Rs. <?= number_format($total, 2) ?></span>
                </div>

                <div class="mt-5">
                    <a href="checkout.php" class="block text-center bg-green-600 text-white px-4 py-3 rounded hover:bg-green-700">
                        Place Order / Checkout
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <p class="text-gray-600">Your cart is empty.</p>
            <a href="menu.php" class="mt-3 inline-block text-indigo-600 hover:underline font-semibold">
                Browse menu
            </a>
        </div>
    <?php endif; ?>

</div>

<?php
require '../includes/layout-end.php';
?>

