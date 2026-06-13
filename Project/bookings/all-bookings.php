<?php 

require 'config/database.php';
require 'includes/auth.php';
require 'includes/layout-start.php';

$stmt = $pdo->prepare('SELECT * FROM bookings');
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/navbar.php';
?>
<div class="max-w-6xl mx-auto p-6">
    <h1>All Bookings</h1>
    <table class="w-full bg-white rounded shadow-md"">
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
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($booking['space_name']); ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'includes/layout-end.php'; ?>



