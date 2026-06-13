<?php 

require '../config/database.php';
require '../includes/auth.php';
include '../includes/layout-start.php';

$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    // Update the quantity in the database
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$quantity, $cart_id, $_SESSION['user_id']]);

    // Redirect back to the cart page
    header('Location: cart.php');
    exit;
}

?>

<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">
        Edit Cart Item
    </h1>

    <?php if (count($cart_items) > 0): ?>
        <form method="POST" class="bg-white shadow-md rounded-lg p-6">
            <input type="hidden" name="cart_id" value="<?php echo $cart_items[0]['id']; ?>">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Item Name:</label>
                <p class="text-gray-900"><?php echo htmlspecialchars($cart_items[0]['item_name']); ?></p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                <p class="text-gray-900">Rs. <?php echo number_format($cart_items[0]['price'], 2); ?></p>
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo $cart_items[0]['quantity']; ?>" min="1"
                    class="border p-3 rounded w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Cart</button>
        </form>
    <?php else: ?>
        <p class="text-gray-600">No items in cart to edit.</p>
    <?php endif; ?>

<?php
include '../includes/layout-end.php';
?>

