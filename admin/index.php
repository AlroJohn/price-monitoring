<?php
// Start session to check user type
session_start();

// Restrict access to non-admin users
if ($_SESSION['TYPE'] !== 'Admin') {
    header('Location: ../index.php');
    exit();
}

require('../includes/connection.php');

// Fetch total numbers for statistics
$storeQuery = "SELECT COUNT(*) AS totalStores FROM stores WHERE SHOP_ID != 1";
$storeResult = mysqli_query($db, $storeQuery);
$totalStores = mysqli_fetch_assoc($storeResult)['totalStores'];

$productQuery = "SELECT COUNT(*) AS totalProducts FROM product";
$productResult = mysqli_query($db, $productQuery);
$totalProducts = mysqli_fetch_assoc($productResult)['totalProducts'];

$userQuery = "SELECT COUNT(*) AS totalUsers FROM owners WHERE STATUS = 'Active'";
$userResult = mysqli_query($db, $userQuery);
$totalUsers = mysqli_fetch_assoc($userResult)['totalUsers'];

$pendingOwnerQuery = "SELECT COUNT(*) AS pendingOwners FROM owners WHERE STATUS = 'Pending'";
$pendingOwnerResult = mysqli_query($db, $pendingOwnerQuery);
$pendingOwners = mysqli_fetch_assoc($pendingOwnerResult)['pendingOwners'];

// Fetch data for graphs
function fetchData($db, $query)
{
    $result = mysqli_query($db, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['month']] = $row['count'];
    }
    return $data;
}

$storeData = fetchData($db, "SELECT MONTHNAME(TIMESTAMP) AS month, COUNT(*) AS count FROM stores WHERE SHOP_ID != 1 GROUP BY month ORDER BY STR_TO_DATE(month, '%M')");
$productData = fetchData($db, "SELECT MONTHNAME(DATE_STOCK_IN) AS month, COUNT(*) AS count FROM product GROUP BY month ORDER BY STR_TO_DATE(month, '%M')");
$userData = fetchData($db, "SELECT MONTHNAME(HIRED_DATE) AS month, COUNT(*) AS count FROM owners WHERE STATUS = 'Active' GROUP BY month ORDER BY STR_TO_DATE(month, '%M')");
$pendingData = fetchData($db, "SELECT MONTHNAME(HIRED_DATE) AS month, COUNT(*) AS count FROM owners WHERE STATUS = 'Pending' GROUP BY month ORDER BY STR_TO_DATE(month, '%M')");

$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$storeCounts = array_map(fn($m) => $storeData[$m] ?? 0, $months);
$productCounts = array_map(fn($m) => $productData[$m] ?? 0, $months);
$userCounts = array_map(fn($m) => $userData[$m] ?? 0, $months);
$pendingCounts = array_map(fn($m) => $pendingData[$m] ?? 0, $months);

$categoryQuery = "
    SELECT st.CATEGORY, COUNT(s.STORE_ID) AS total 
    FROM stores s
    JOIN store_type st ON s.STORE_ID = st.STORE_ID
    WHERE s.SHOP_ID != 1
    GROUP BY st.CATEGORY";

$categoryResult = mysqli_query($db, $categoryQuery);

$storeCategories = [];
while ($row = mysqli_fetch_assoc($categoryResult)) {
    $storeCategories[$row['CATEGORY']] = $row['total'];
}

function fetchProductData($db)
{
    $query = "
        SELECT DATE_FORMAT(DATE_STOCK_IN, '%Y-%m') AS year_month, COUNT(*) AS count
        FROM product
        GROUP BY DATE_FORMAT(DATE_STOCK_IN, '%Y-%m')
        ORDER BY year_month DESC
        LIMIT 12";  // Only get the last 12 months

    $result = mysqli_query($db, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['year_month']] = $row['count'];
    }
    return $data;
}

include('../includes/sidebar.php'); // Sidebar for navigation
?>

<div class="container mx-auto p-6">
    <h1 class="text-5xl font-extrabold text-gray-900 mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-6 rounded-2xl shadow-xl text-white flex items-center">
            <i class="fas fa-store text-5xl"></i>
            <div class="ml-4">
                <h2 class="text-xl font-semibold">Total Stores</h2>
                <p class="text-4xl font-bold"><?php echo $totalStores; ?></p>
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-green-500 to-green-700 p-6 rounded-2xl shadow-xl text-white flex items-center">
            <i class="fas fa-tools text-5xl"></i>
            <div class="ml-4">
                <h2 class="text-xl font-semibold">Total Hardware</h2>
                <p class="text-4xl font-bold"><?php echo $storeCategories['Hardware'] ?? 0; ?></p>
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-purple-500 to-purple-700 p-6 rounded-2xl shadow-xl text-white flex items-center">
            <i class="fas fa-drumstick-bite text-5xl"></i>
            <div class="ml-4">
                <h2 class="text-xl font-semibold">Total Meatshop</h2>
                <p class="text-4xl font-bold"><?php echo $storeCategories['Meatshop'] ?? 0; ?></p>
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-yellow-500 to-yellow-700 p-6 rounded-2xl shadow-xl text-white flex items-center">
            <i class="fas fa-tractor text-5xl"></i>
            <div class="ml-4">
                <h2 class="text-xl font-semibold">Total Poultry (Animal Feeds)</h2>
                <p class="text-4xl font-bold"><?php echo $storeCategories['Poultry'] ?? 0; ?></p>
            </div>
        </div>

    </div>

    <!-- Graph & Data Table Section -->
    <div class="mt-10">
        <div class="p-6 bg-white rounded-2xl shadow-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Stores Added Per Month</h2>
            <canvas id="storeChart"></canvas>
        </div>

        <div class="mt-10 p-6 bg-white rounded-2xl shadow-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Products Added Per Month</h2>
            <canvas id="productChart"></canvas>
        </div>

        <div class="mt-10 p-6 bg-white rounded-2xl shadow-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Active Users Per Month</h2>
            <canvas id="userChart"></canvas>
        </div>

        <div class="mt-10 p-6 bg-white rounded-2xl shadow-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Pending Approvals Per Month</h2>
            <canvas id="pendingChart"></canvas>
        </div>

        <!-- Data Table for Monthly Statistics -->
        <div class="mt-10 p-6 bg-white rounded-2xl shadow-xl overflow-x-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Monthly Data Summary</h2>
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">Month</th>
                        <th class="border border-gray-300 px-4 py-2">Stores Added</th>
                        <th class="border border-gray-300 px-4 py-2">Products Added</th>
                        <th class="border border-gray-300 px-4 py-2">Active Users</th>
                        <th class="border border-gray-300 px-4 py-2">Pending Approvals</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($months as $month): ?>
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2 font-semibold"><?php echo $month; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $storeData[$month] ?? 0; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $productData[$month] ?? 0; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $userData[$month] ?? 0; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $pendingData[$month] ?? 0; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function createChart(ctx, label, data, color) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($months); ?>,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: color
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }

        createChart(document.getElementById('storeChart').getContext('2d'), 'Stores Added', <?php echo json_encode($storeCounts); ?>, '#3b82f6');
        createChart(document.getElementById('productChart').getContext('2d'), 'Products Added', <?php echo json_encode($productCounts); ?>, '#10b981');
        createChart(document.getElementById('userChart').getContext('2d'), 'Active Users', <?php echo json_encode($userCounts); ?>, '#8b5cf6');
        createChart(document.getElementById('pendingChart').getContext('2d'), 'Pending Approvals', <?php echo json_encode($pendingCounts); ?>, '#f59e0b');
    </script>
    <script>
        function createChart(ctx, label, labels, data, color) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,  // Updated to show "YYYY-MM"
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: color
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }

        createChart(document.getElementById('productChart').getContext('2d'), 'Products Added',
            <?php echo json_encode($productMonths); ?>,
            <?php echo json_encode($productCounts); ?>,
            '#10b981');
    </script>