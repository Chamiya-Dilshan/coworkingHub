<?php

require '../config/database.php';
require '../includes/auth.php';
require '../includes/layout-start.php';

$id = $_GET['id'] ?? null;
if(!$id) {
    header('Location: bookings.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? and user_id = ? ");
$stmt->execute([$id, $_SESSION['user_id']]);
$booking = $stmt->fetch();


if(!$booking) {
    die('Booking not found or you do not have permission to edit this booking.');
}

$message = '';

if(isset($_POST['update'])) {
    $space_name = $_POST['space_name'];
    $booking_date = $_POST['booking_date'];         
    $booking_time = $_POST['booking_time'];

    if(empty($space_name) || empty($booking_date) || empty($booking_time)) {
        $message = 'All fields are required.';
    } else {
        $check = $pdo->prepare("SELECT * FROM bookings WHERE space_name = ? AND booking_date = ? AND booking_time = ? AND id != ?");
        $check->execute([$space_name, $booking_date, $booking_time, $id]);  
        if($check->rowCount() > 0) {
            $message = 'This space is already booked for the selected date and time.';
        } else {
            $stmt = $pdo->prepare("UPDATE bookings SET space_name = ?, booking_date = ?, booking_time = ? WHERE id = ? AND user_id = ?");
            if($stmt->execute([$space_name, $booking_date, $booking_time, $id, $_SESSION['user_id']])) {
                header('Location: bookings.php');
                exit;
            } else {
                $message = 'Failed to update booking. Please try again.';
            }
        }
    }
}

?>
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Booking</h1>
    <?php if($message): ?>
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST" class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <label for="space_name" class="block text-gray-700">Space Name</label>
            <input type="text" name="space_name" id="space_name" class="w-full border border-gray-300 p-2 rounded" value="<?php echo htmlspecialchars($booking['space_name']); ?>" required>
        </div>
        <div class="mb-4">
            <label for="booking_date" class="block text-gray-700">Booking Date</label>
            <input type="date" name="booking_date" id="booking_date" class=" w-full border border-gray-300 p-2 rounded" value="<?php echo htmlspecialchars($booking['booking_date']); ?>" required>
        </div>
        <div class="mb-4">
            <label for="booking_time" class="block text-gray-700">Booking Time</label>
            <input type="time" name="booking_time" id="booking_time" class="w-full border border-gray-300 p-2 rounded" value="<?php echo htmlspecialchars($booking['booking_time']); ?>" required>
        </div>
        <button type="submit" name="update" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update Booking</button>
    </form>
</div>

<?php
include '../includes/layout-start.php';
?>