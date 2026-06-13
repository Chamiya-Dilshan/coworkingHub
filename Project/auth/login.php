<?php

session_start();
require_once '../config/database.php';
require_once '../includes/layout-start.php';

$message = "";
if(isset($_SESSION['user_id']))
{
    header("Location: ../dashboard.php");
    exit();
}


if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare(
        "SELECT * from users where email=?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if(
        $user && password_verify(
            $password,
            $user['password']
        )
    )
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];

        header("Location: ../dashboard.php");
        exit();
    }
    else
    {
        $message = "Invalid Email or Password";
    }
}
?>


<div class="min-h-screen flex items-center justify-end px-20" style="background-image: url('../assets/images/login.avif'); background-size: cover; background-repeat: no-repeat;">
    <div class="bg-white shadow-lg rounded-xl p-8 w-96 backdrop-blur-sm bg-opacity-20 border border-white border-opacity-30">
        <h2 class="text-3xl font-bold mb-6 text-center">Login</h2>
        <?php if(isset($_GET['success'])): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            Registration Successful
        </div>
        <?php endif; ?>
        <?php if($message): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?= $message ?>
        </div>
        <?php endif; ?>
        <form class="bg-white shadow-xl border rounded-xl px-8 pt-6 pb-8 mb-4" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="email" name="email" type="email" placeholder="Email">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    id="password" name="password" type="password" placeholder="Password">
            </div>
            <div class="flex items-center justify-between">
                <button
                    class="bg-blue-500 hover:scale-105 transition-transform duration-300 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit" name="login">
                    Login
                </button>
            </div>
        </form>
        <p class="text-center mt-4">
            Don't have an account ?
            <a href="register.php" class="text-indigo-600">
                Register
            </a>
        </p>
    </div>
</div>

<?php include '../includes/layout-end.php'; ?>