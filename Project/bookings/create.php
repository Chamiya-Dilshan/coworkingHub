<?php 

require '../config/database.php';
require '../includes/auth.php';
require '../includes/layout-start.php';

$message = '';

if(isset($_POST['submit'])) {
    $space_name = $_POST['space_name'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $user_id = $_SESSION['user_id'];

    if(empty($space_name) || empty($booking_date) || empty($booking_time)) {
        $message = 'All fields are required.';
    } else {
        $check = $pdo->prepare("SELECT * FROM bookings WHERE space_name = ? AND booking_date = ? AND booking_time = ?");
        $check->execute([$space_name, $booking_date, $booking_time]);  
        if($check->rowCount() > 0) {
            $message = 'This space is already booked for the selected date and time.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, space_name, booking_date, booking_time) VALUES (?, ?, ?, ?)");
            if($stmt->execute([$user_id, $space_name, $booking_date, $booking_time])) {
                header('Location: bookings.php');
                exit;
            } else {
                $message = 'Failed to create booking. Please try again.';
            }
            header('Location: bookings.php');
            exit();
            }
    }
}

include '../includes/navbar.php';
?>
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create Booking</h1>
    <?php if($message): ?>
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST" class="bg-white p-6 rounded-lg shadow-xl">
        <div class="mb-4">
            <label for="space_name" class="block text-gray-700">Space Name</label>
            <input type="text" name="space_name" id="space_name" class="w-full border border-gray-300 p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label for="booking_date" class="block text-gray-700">Booking Date</label>
            <input type="date" name="booking_date" id="booking_date" class="w-full border border-gray-300 p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label for="booking_time" class="block text-gray-700">Booking Time</label>
            <input type="time" name="booking_time" id="booking_time" class="w-full border border-gray-300 p-2 rounded" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Create Booking</button>
    </form>
</div>

<?php include '../includes/layout-end.php'; ?>