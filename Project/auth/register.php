<?php

session_start();
require_once '../config/database.php';
include '../includes/layout-start.php';

$message = "";

function isStrongPassword(string $password): array
{
    $errors = [];

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    }
    if (!preg_match('/\d/', $password)) {
        $errors[] = "Password must contain at least one digit";
    }
    // Special character = anything that's not a-z, A-Z, 0-9
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors[] = "Password must contain at least one special character";
    }

    return $errors;
}

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($name) || empty($email) || empty($password))
    {
        $message = "All fields are required";
    }
    else
        {
            $check = $pdo->prepare(
                "SELECT id FROM users WHERE email = ? "
            );

            $check->execute([$email]);

            if($check->rowCount() > 0)
            {
                $message = "Email already exists";
            }
            else
            {
                $passwordErrors = isStrongPassword($password);
                if (!empty($passwordErrors)) {
                    $message = implode(". ", $passwordErrors);
                }
                else {
                    $hashedPassword =
                        password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        );
                    $stmt = $pdo->prepare(
                        "INSERT into users(name,email,password)
                        values(?,?,?)"
                    );

                    $stmt->execute([
                        htmlspecialchars($name),
                        htmlspecialchars($email),
                        $hashedPassword
                    ]);

                    echo "User Registered Successfully";

                    header("Location: login.php?success=1");
                    exit();
                }
            }
        }
}
?>


<div class="min-h-screen flex items-center justify-end px-20" style="background-image: url('../assets/images/login.avif'); background-size: cover; background-repeat: no-repeat;">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md backdrop-blur-sm bg-opacity-20 border border-white border-opacity-30">
        <h2 class="text-3xl font-bold mb-6 text-center">Register</h2>
        <?php if($message): ?>

        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?= $message ?>
        </div>

        <?php endif; ?>
        <form class="bg-white shadow-xl border rounded-xl px-8 pt-6 pb-8 mb-4 " method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Full Name
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    type="text" name="name" required>

                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Email
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    type="email" name="email" required>

                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    type="password" name="password" required>
                <button
                    class="bg-blue-500 hover:scale-105 transition-transform duration-300 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit" name="register">Register</button>
            </div>
        </form>
        <p class="text-center mt-4">
            already have an account ?
            <a href="login.php" class="text-indigo-600">
                Login
            </a>
        </p>
    </div>
</div>

<?php include '../includes/layout-end.php'; ?>