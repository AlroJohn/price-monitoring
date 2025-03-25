<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get current file name
include '../includes/connection.php'; // Database connection

// Fetch pending approvals count
$pending_query = "SELECT COUNT(*) AS pending_count FROM owners WHERE STATUS = 'Pending'";
$pending_result = mysqli_query($db, $pending_query);
$pending_row = mysqli_fetch_assoc($pending_result);
$pending_approvals = $pending_row['pending_count']; // Get the count
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- FontAwesome Online Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <script>
    function toggleDropdown(id, arrowId) {
      document.getElementById(id).classList.toggle("hidden");
      document.getElementById(arrowId).classList.toggle("rotate-180"); // Rotate arrow
    }
  </script>

</head>

<body class="bg-gray-100 flex">
  <!-- Sidebar -->
  <div class="h-screen w-64 bg-gray-900 text-white flex flex-col">
    <div class="flex items-center justify-center py-4 border-b border-gray-700">
      <img src="../icon/store/icons8-admin-64.png" class="h-10 w-10">
      <span class="text-xl font-bold ml-2">ADMIN PANEL</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-2 space-y-2">
      <a href="index.php"
        class="flex items-center py-2 px-3 rounded-md <?= $current_page == 'index.php' ? 'bg-gray-800' : 'hover:bg-gray-800' ?>">
        <i class="fa-solid fa-gauge text-blue-400 mr-2"></i>
        <span>Dashboard</span>
      </a>

      <h2 class="text-xs uppercase text-gray-500 mt-4 mb-2">Management</h2>

      <!-- Stores Dropdown -->
      <div>
        <button onclick="toggleDropdown('storesDropdown', 'storeArrow')"
          class="flex items-center justify-between w-full py-2 px-3 rounded-md hover:bg-gray-800">
          <div class="flex items-center">
            <i class="fa-solid fa-store text-green-400 mr-2"></i>
            <span>Stores</span>
          </div>
          <i id="storeArrow" class="fa-solid fa-chevron-down transition-transform"></i>
        </button>
        <div id="storesDropdown" class="hidden bg-gray-800 ml-4 rounded-md mt-1">
          <a href="stores_meatshop.php" class="block py-2 px-3 hover:bg-gray-700">Meatshop</a>
          <a href="stores_animal_feeds.php" class="block py-2 px-3 hover:bg-gray-700">Animal Feeds</a>
          <a href="stores_hardware.php" class="block py-2 px-3 hover:bg-gray-700">Hardware</a>
        </div>
      </div>

      <!-- Products Dropdown -->
      <div>
        <button onclick="toggleDropdown('productsDropdown', 'productArrow')"
          class="flex items-center justify-between w-full py-2 px-3 rounded-md hover:bg-gray-800">
          <div class="flex items-center">
            <i class="fa-solid fa-box text-yellow-400 mr-2"></i>
            <span>Products</span>
          </div>
          <i id="productArrow" class="fa-solid fa-chevron-down transition-transform"></i>
        </button>
        <div id="productsDropdown" class="hidden bg-gray-800 ml-4 rounded-md mt-1">
          <a href="products_meatshop.php" class="block py-2 px-3 hover:bg-gray-700">Meatshop Products</a>
          <a href="products_animal_feeds.php" class="block py-2 px-3 hover:bg-gray-700">Animal Feeds Products</a>
          <a href="products_hardware.php" class="block py-2 px-3 hover:bg-gray-700">Hardware Products</a>
        </div>
      </div>

      <a href="users.php"
        class="flex items-center py-2 px-3 rounded-md <?= $current_page == 'users.php' ? 'bg-gray-800' : 'hover:bg-gray-800' ?>">
        <i class="fa-solid fa-users text-purple-400 mr-2"></i>
        <span>Users</span>
      </a>

      <a href="approvals.php"
        class="flex items-center py-2 px-3 rounded-md <?= $current_page == 'approvals.php' ? 'bg-gray-800' : 'hover:bg-gray-800' ?>">
        <i class="fa-solid fa-check-circle text-blue-400 mr-2"></i>
        <span>Approvals</span>

        <?php if ($pending_approvals > 0): ?>
          <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
            <?= $pending_approvals ?>
          </span>
        <?php endif; ?>

      </a>

      <h2 class="text-xs uppercase text-gray-500 mt-4 mb-2">Reports</h2>

      <a href="sales_report.php"
        class="flex items-center py-2 px-3 rounded-md <?= $current_page == 'sales_report.php' ? 'bg-gray-800' : 'hover:bg-gray-800' ?>">
        <i class="fa-solid fa-chart-line text-red-400 mr-2"></i>
        <span>Sales Report</span>
      </a>

      <a href="activity_log.php"
        class="flex items-center py-2 px-3 rounded-md <?= $current_page == 'activity_log.php' ? 'bg-gray-800' : 'hover:bg-gray-800' ?>">
        <i class="fa-solid fa-list text-orange-400 mr-2"></i>
        <span>Activity Log</span>
      </a>
    </nav>

    <!-- Logout Button -->
    <div class="p-4">
      <a href="logout.php" class="w-full block text-center bg-red-600 hover:bg-red-500 py-2 rounded-md text-sm">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </div>

  <!-- Content Area -->
  <div class="flex-1 p-10">

</html>