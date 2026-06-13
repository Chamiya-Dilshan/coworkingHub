<?php

require '../config/database.php';
require '../includes/auth.php';
require '../includes/layout-start.php';

$search = $_GET['search'] ?? '';

if(!empty($search)) {
    $stmt = $pdo->prepare('SELECT * FROM cafe_items WHERE name LIKE ?');
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query('SELECT * FROM cafe_items');
}
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <h1 class="text-3xl font-bold mb-6">
        Cafe Menu
    </h1>


    <form method="GET" class="mb-6">


        <input type="text" name="search" placeholder="Search menu..." value="<?= htmlspecialchars($search) ?>"
            class="border p-3 bg-gray-300 rounded-xl w-full">

    </form>
    <div class="grid md:grid-cols-3 gap-6">

        <?php foreach($items as $item): ?>

        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <img src="<?= $item['image'] ?>" class="h-48 w-full object-cover">

            <div class="p-5">

                <h2 class="text-xl font-bold">
                    <?= htmlspecialchars($item['name']) ?>
                </h2>

                <p class="text-green-600 font-semibold mt-2">
                    Rs.
                    <?= number_format($item['price'],2) ?>
                </p>
                
                <a href="add-cart.php?id=<?= $item['id'] ?>"
                    class="block text-center bg-indigo-600 text-white mt-4 py-3 rounded-lg">

                    Add To Cart

                </a>

            </div>

        </div>
        <?php endforeach; ?>

    </div>
</div>

<?php require '../includes/layout-end.php'; ?>