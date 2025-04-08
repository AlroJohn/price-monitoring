<?php
include('../../includes/connection.php');

if (!isset($_GET['shop_id'])) {
    echo "<p class='text-red-500 text-center mt-10'>Invalid Store.</p>";
    exit();
}

$shop_id = mysqli_real_escape_string($db, $_GET['shop_id']);

// Fetch Store Details
$storeQuery = "SELECT s.*, o.LOCATION_ID, l.PUROK, l.BARANGAY 
               FROM stores s
               JOIN owners o ON s.SHOP_ID = o.SHOP_ID
               JOIN location l ON o.LOCATION_ID = l.LOCATION_ID
               WHERE s.SHOP_ID = '$shop_id'";
$storeResult = mysqli_query($db, $storeQuery);
$store = mysqli_fetch_assoc($storeResult);

if (!$store) {
    echo "<p class='text-red-500 text-center mt-10'>Store not found.</p>";
    exit();
}

// Handle store image
$storeImg = "../../assets/store_img/{$store['IMAGE']}";
if (!file_exists($storeImg) || empty($store['IMAGE'])) {
    $storeImg = "../../assets/store_img/no_img.jpg";
}

// Fetch Store Products
$productQuery = "SELECT * FROM product WHERE SHOP_ID = '$shop_id' AND pro_status = 'active'";
$productResult = mysqli_query($db, $productQuery);
$totalProducts = mysqli_num_rows($productResult);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($store['SHOP_NAME']); ?> - Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="font-sans bg-gray-100">
    <!-- Include Topbar -->
    <?php include '../../user/includes/topbar.php'; ?>

    <section class="container mx-auto my-5 px-4">
        <!-- Back Button -->
        <a href="javascript:history.back()"
            class="inline-block bg-gray-700 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-800 transition">
            <i class="fas fa-reply mr-2"></i> Back
        </a>


        <!-- Store Info -->
        <div class="bg-white p-6 shadow-lg rounded-lg grid grid-cols-3 items-center gap-4 mt-4">
            <!-- Left Column: Store Details -->
            <div class="flex items-center space-x-4">
                <img src="<?php echo $storeImg; ?>" alt="Store Logo" class="h-16 w-16 object-cover rounded-lg shadow">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 leading-tight">
                        <?php echo htmlspecialchars($store['SHOP_NAME']); ?>
                    </h3>
                    <p
                        class="text-<?php echo $store['AVAILABILITY'] == 'Open' ? 'green' : 'red'; ?>-500 font-semibold flex items-center">
                        <i class="fas fa-circle mr-2"></i> <?php echo $store['AVAILABILITY']; ?>
                    </p>
                    <p class="text-gray-600 text-sm flex items-center">
                        <i class="fas fa-clock mr-2 text-yellow-500"></i>
                        <?php echo date("h:i A", strtotime($store['TIME_OPEN'])) . " - " . date("h:i A", strtotime($store['TIME_CLOSE'])); ?>
                    </p>
                </div>
            </div>

            <!-- Center Column: Store Location -->
            <div class="flex items-center justify-center text-gray-700 font-semibold">
                <i class="fas fa-map-marker-alt text-blue-500 mr-2 text-lg"></i>
                <span class="text-lg">
                    <?php echo htmlspecialchars($store['PUROK']) . ', ' . htmlspecialchars($store['BARANGAY']) . ', Daraga, Albay'; ?>
                </span>
            </div>

            <!-- Rightmost Column: Total Products -->
            <div class="flex items-center justify-end text-gray-700 font-semibold">
                <i class="fas fa-box-open text-blue-500 mr-2 text-lg"></i>
                <span class="text-lg">Total Products: <?php echo $totalProducts; ?></span>
            </div>
        </div>


        <!-- Product Listing -->
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
            <?php while ($product = mysqli_fetch_assoc($productResult)) {
                $prodImg = "../../assets/product_img/{$product['IMAGE']}";
                if (!file_exists($prodImg) || empty($product['IMAGE'])) {
                    $prodImg = "../../assets/product_img/no_img.jpg";
                }
                ?>
                <div class="bg-white p-3 rounded-lg shadow text-center">
                    <img src="<?php echo $prodImg; ?>" alt="Product Image" class="h-24 w-full object-cover rounded-lg mb-2">
                    <h3 class="text-sm font-semibold text-gray-800 truncate">
                        <?php echo htmlspecialchars($product['NAME']); ?>
                    </h3>
                    <p class="text-xs text-gray-600 truncate">
                        <?php echo strlen($product['DESCRIPTION']) > 50 ? substr(htmlspecialchars($product['DESCRIPTION']), 0, 50) . '...' : htmlspecialchars($product['DESCRIPTION']); ?>
                    </p>
                    <p class="text-blue-500 text-sm font-bold mt-1">₱<?php echo number_format($product['PRICE'], 2); ?></p>
                    <a href="reserve_index.php?shop_id=<?php echo $shop_id; ?>&product_code=<?php echo $product['PRODUCT_CODE']; ?>"
                        class="block bg-green-500 text-white text-xs py-1 mt-2 rounded shadow hover:bg-green-600">
                        Reserve
                    </a>
                </div>
            <?php } ?>
        </div>
    </section>

    <?php

    // Fetch the saved location from the database
    $query = "SELECT LATITUDE, LONGITUDE FROM store_locations WHERE SHOP_ID = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $shop_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $location = mysqli_fetch_assoc($result);

    // If a location exists, use it; otherwise, use a default location (Daraga, Albay)
    $latitude = $location ? $location['LATITUDE'] : 13.1435;
    $longitude = $location ? $location['LONGITUDE'] : 123.7336;
    ?>

    <div id="map" class="w-full h-64 rounded-lg overflow-hidden shadow"></div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        var latitude = <?= json_encode($latitude) ?>;
        var longitude = <?= json_encode($longitude) ?>;

        var map = L.map('map').setView([latitude, longitude], 15); // Set to saved location

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([latitude, longitude]).addTo(map)
            .bindPopup("Store Location")
            .openPopup();
    </script>


</body>

</html>