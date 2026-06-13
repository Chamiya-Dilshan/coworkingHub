<?php

require '../config/database.php';
require '../includes/auth.php';
require '../includes/layout-start.php';

$stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();

include '../includes/navbar.php';
?>
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">My Bookings</h1>
    <a href="create.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">Create New Booking</a>
    <?php if(count($bookings) > 0): ?>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">Space Name</th>
                    <th class="px-4 py-2">Booking Date</th>
                    <th class="px-4 py-2">Booking Time</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($bookings as $booking): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo htmlspecialchars($booking['space_name']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                        <td class="px-4 py-2">
                            <a href="edit.php?id=<?php echo $booking['id']; ?>" class="text-blue-500 hover:underline">Edit</a>
                            <a href="delete.php?id=<?php echo $booking['id']; ?>" class="text-blue-500 hover:underline" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>    
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no bookings yet.</p>
    <?php endif; ?>
</div>
<?php include '../includes/layout-end.php'; ?>

