<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Compact Navigation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-blue-600 shadow-md py-0">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <!-- Left Side (Empty for future use) -->
            <div class="w-2/3 text-white">
                <h5>Daraga, Albay (4502)</h5>
            </div>



            <!-- Right Side (Links with Icons) -->
            <div class="w-1/3 flex justify-end space-x-6">
                <a href="track_registration.php" id="track-link"
                    class="flex items-center space-x-1 text-white py-2 px-2 rounded-md hover:bg-blue-700 transition-colors whitespace-nowrap">
                    <i class="ph ph-map-trifold text-lg"></i>
                    <span>Track Registration</span>
                </a>
                <a href="../../admin/create_account.php" id="register-link"
                    class="flex items-center space-x-1 text-white py-2 px-2 rounded-md hover:bg-blue-700 transition-colors whitespace-nowrap">
                    <i class="ph ph-user-plus text-lg"></i>
                    <span>Register Account</span>
                </a>
                <a href="../../admin/login-admin.php" id="login-link"
                    class="flex items-center space-x-1 text-white py-2 px-2 rounded-md hover:bg-blue-700 transition-colors whitespace-nowrap">
                    <i class="ph ph-sign-in text-lg"></i>
                    <span>Login</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- JavaScript for Active Link Highlighting -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const path = window.location.pathname;

            if (path.includes("track.php")) {
                document.getElementById("track-link").classList.add("text-blue-600", "font-bold");
            } else if (path.includes("create_account.php")) {
                document.getElementById("register-link").classList.add("text-blue-600", "font-bold");
            } else if (path.includes("login-admin.php")) {
                document.getElementById("login-link").classList.add("text-blue-600", "font-bold");
            }
        });
    </script>
</body>

</html>