<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Single Top Navigation -->
    <div class="bg-blue-600 shadow-md py-3 px-6 flex justify-between items-center">
        <!-- Logo -->
        <div>
            <img src="../img/logo.png" alt="Logo" class="h-16">
        </div>

        <!-- Navigation Links -->
        <div>
            <a href="../user/pages/index.php" class="text-white text-lg font-semibold hover:underline">
                Go to Homepage
            </a>
        </div>
    </div>

    <!-- Centered Login Form -->
    <div class="flex justify-center items-center flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold text-center mb-6">Store Login</h2>

            <!-- Error Messages -->
            <?php
            if (isset($_GET['missingpassword'])) {
                echo '<p class="text-red-500 text-sm text-center">Password is required.</p>';
            } elseif (isset($_GET['pending'])) {
                echo '<p class="text-yellow-500 text-sm text-center">Your account is under review.</p>';
            } elseif (isset($_GET['wrongcredentials'])) {
                echo '<p class="text-red-500 text-sm text-center">Invalid username or password.</p>';
            } elseif (isset($_GET['usernotexist'])) {
                echo '<p class="text-red-500 text-sm text-center">User does not exist.</p>';
            }
            ?>

            <form action="login_admin_process.php" method="POST" class="mt-4">
                <div class="mb-4">
                    <label for="user" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="user" name="user" required
                        class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                        class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Login
                </button>
            </form>

            <!-- Register Business Button -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Don't have a business account?</p>
                <a href="../admin/create_account2.php"
                    class="mt-2 inline-block bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition">
                    Register Business
                </a>
            </div>
        </div>
    </div>
</body>

</html>