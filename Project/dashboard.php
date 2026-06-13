<?php

session_start();
require 'config/database.php';
require 'includes/auth.php';
require 'includes/layout-start.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: /auth/login.php');
    exit();
}

$userId = $_SESSION['user_id'];

    $bookingCount = 
        $pdo->prepare(
            "select count(*) from bookings where user_id=?"
        );

    $bookingCount->execute([$userId]);
    $totalBookings = (int)($bookingCount->fetchColumn() ?? 0);

    $cartCount = 
        $pdo->prepare(
            "select SUM(quantity) from cart where user_id=?"
        );

    $cartCount->execute([$userId]);
    $totalCart = (int)($cartCount->fetchColumn() ?? 0);

    $stmt = $pdo->prepare('SELECT * FROM bookings');
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    include 'includes/navbar.php';

?>

<div class="max-w-7xl mx-auto p-6">

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

    <h1 class="text-3xl font-bold mb-6">
        Welcome,
        <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
    </h1>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-xl">
            <h2 class="text-grey-500">
                Total Bookings
            </h2>
            <p class="text-4xl font-bold text-indigo-600">
                <?php echo $totalBookings; ?>
            </p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-xl">
            <h2 class="text-grey-500">
                Cart Items
            </h2>
            <p class="text-4xl font-bold text-green-600">
                <?php echo $totalCart ?? 0; ?>
            </p>
        </div>
    </div>
    <div class="mt-8 bg-white p-6 rounded-2xl shadow-xl">
        <h2 class="text-2xl font-semibold mb-4">
            Quick Actions
        </h2>
        <div class="flex flex-wrap gap-4">
            <a href="bookings/create.php" class="bg-indigo-600 text-white px-5 py-3 rounded-lg">
                Create Booking
            </a>

            <a href="bookings/bookings.php" class="bg-blue-600 text-white px-5 py-3 rounded-lg">
                My Bookings
            </a>

            <a href="cafe/menu.php" class="bg-green-600 text-white px-5 py-3 rounded-lg">
                Browse cafe
            </a>

            <a href="cafe/cart.php" class="bg-red-600 text-white px-5 py-3 rounded-lg">
                view cart
            </a>
        </div>
    </div>
    <div class="mt-8 bg-white p-6 rounded-2xl shadow-xl">
        <div class="container mt-5">
            <h2 class="text-2xl font-semibold mb-4">
                All Bookings
            </h2>
            <table class="w-full bg-white rounded shadow-md">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Space Name</th>
                        <th class="border px-4 py-2">Booking Date</th>
                        <th class="border px-4 py-2">Booking Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td class="border px-4 py-2">
                            <?php echo htmlspecialchars($booking['space_name']); ?>
                        </td>
                        <td class="border px-4 py-2">
                            <?php echo htmlspecialchars($booking['booking_date']); ?>
                        </td>
                        <td class="border px-4 py-2">
                            <?php echo htmlspecialchars($booking['booking_time']); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
include  './includes/layout-end.php';
?>