<?php
session_start();
include '../../includes/connection.php';

$shop_id = $_SESSION['SHOP_ID'];
$message = isset($_SESSION['message']) ? $_SESSION['message'] : "";
unset($_SESSION['message']); // Clear message after displaying

// Fetch store details (Location, Availability, and Store Hours)
$query = "SELECT s.AVAILABILITY, s.TIME_OPEN, s.TIME_CLOSE, sl.LATITUDE, sl.LONGITUDE 
          FROM stores s 
          LEFT JOIN store_locations sl ON s.SHOP_ID = sl.SHOP_ID 
          WHERE s.SHOP_ID = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $shop_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$store = mysqli_fetch_assoc($result);


// Set default values
$latitude = $store['LATITUDE'] ?? 13.1339;
$longitude = $store['LONGITUDE'] ?? 123.7334;
$availability = $store['AVAILABILITY'] ?? 'Close';
$time_open = $store['TIME_OPEN'] ?? '08:00:00';
$time_close = $store['TIME_CLOSE'] ?? '18:00:00';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Location & Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var storedLat = <?= json_encode($latitude) ?>;
            var storedLng = <?= json_encode($longitude) ?>;
            var map = L.map("map").setView([storedLat, storedLng], 15);

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "&copy; OpenStreetMap contributors"
            }).addTo(map);

            var marker = L.marker([storedLat, storedLng], { draggable: true }).addTo(map);
            marker.on("dragend", function (event) {
                var position = marker.getLatLng();
                document.getElementById("latitude").value = position.lat;
                document.getElementById("longitude").value = position.lng;
            });

            // Auto-hide notification
            var notification = document.getElementById("notification");
            if (notification) {
                setTimeout(() => notification.style.display = "none", 3000);
            }
        });

        function toggleStoreStatus() {
            var checkbox = document.getElementById("store_status");
            var statusText = document.getElementById("status_text");
            var newStatus = checkbox.checked ? "Open" : "Close";

            // Instantly update UI
            statusText.innerText = newStatus;
            statusText.classList.toggle("text-green-600", checkbox.checked);
            statusText.classList.toggle("text-red-600", !checkbox.checked);

            // Send status update to backend
            fetch("update_store_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "status=" + newStatus + "&shop_id=<?= $shop_id ?>"
            });
        }
    </script>

</head>

<body>
    <?php include '../include/topbar.php'; ?>

    <!-- Notification -->
    <?php if (!empty($message)): ?>
        <div id="notification" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md mb-4 text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="max-w-5xl mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow-md mb-4 text-gray-900">
            <h2 class="text-xl font-bold mb-3">Store Status & Operating Hours</h2>

            <!-- Store Status Toggle -->
            <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg">
                <span class="font-semibold">Store is currently:</span>
                <div class="flex items-center">
                    <span id="status_text"
                        class="mr-2 text-lg font-bold <?= $availability == 'Open' ? 'text-green-600' : 'text-red-600' ?>">
                        <?= $availability ?>
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="store_status" class="sr-only peer" <?= $availability == 'Open' ? 'checked' : '' ?> onchange="toggleStoreStatus()">
                        <div
                            class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-green-600 peer-checked:before:translate-x-6 transition-all relative before:absolute before:top-1 before:left-1 before:bg-white before:w-4 before:h-4 before:rounded-full before:shadow-md">
                        </div>
                    </label>
                </div>
            </div>

            <!-- Store Hours -->
            <form action="update_store_hours.php" method="POST" class="mt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold">Time Open</label>
                        <input type="time" name="time_open" value="<?= htmlspecialchars($time_open) ?>"
                            class="border p-2 w-full rounded-md">
                    </div>
                    <div>
                        <label class="font-semibold">Time Close</label>
                        <input type="time" name="time_close" value="<?= htmlspecialchars($time_close) ?>"
                            class="border p-2 w-full rounded-md">
                    </div>
                </div>
                <button type="submit"
                    class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full">
                    Save Store Hours
                </button>
            </form>
        </div>



        <h2 class="text-xl font-bold mb-3">Pin Your Store Location</h2>
        <div id="map" class="w-full h-96 rounded-lg shadow-md mb-4"></div>

        <form action="map_save.php" method="POST">
            <input type="hidden" id="latitude" name="latitude" value="<?= htmlspecialchars($latitude) ?>">
            <input type="hidden" id="longitude" name="longitude" value="<?= htmlspecialchars($longitude) ?>">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Save Location
            </button>
        </form>
    </div>
</body>

</html>