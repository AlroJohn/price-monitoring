<?php
session_start();
require('../../includes/connection.php');

$shop_id = isset($_POST['shop_id']) ? $_POST['shop_id'] : '';
$store = $storeType = $owner = $location = null;

if (!empty($shop_id)) {
    $query = "SELECT * FROM stores WHERE SHOP_ID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $store = $result->fetch_assoc();

    if ($store) {
        $storeTypeQuery = "SELECT * FROM store_type WHERE STORE_ID = ?";
        $storeTypeStmt = $db->prepare($storeTypeQuery);
        $storeTypeStmt->bind_param('i', $store['STORE_ID']);
        $storeTypeStmt->execute();
        $storeTypeResult = $storeTypeStmt->get_result();
        $storeType = $storeTypeResult->fetch_assoc();
    }

    $ownerQuery = "SELECT * FROM owners WHERE SHOP_ID = ?";
    $ownerStmt = $db->prepare($ownerQuery);
    $ownerStmt->bind_param('i', $shop_id);
    $ownerStmt->execute();
    $ownerResult = $ownerStmt->get_result();
    $owner = $ownerResult->fetch_assoc();


    if ($owner) {

        $locationQuery = "SELECT * FROM location WHERE LOCATION_ID = ?";
        $locationStmt = $db->prepare($locationQuery);
        $locationStmt->bind_param('i', $owner['LOCATION_ID']);
        $locationStmt->execute();
        $locationResult = $locationStmt->get_result();
        $location = $locationResult->fetch_assoc();
    }

}

if (isset($_POST['ajax'])) {
    echo json_encode([
        'store' => $store,
        'storeType' => $storeType,
        'owner' => $owner,
        'location' => $location
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Registration</title>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/js/all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        .transparent-box {
            background: rgba(255, 255, 255, 0.3);
            /* White with 80% opacity */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, .9);
            /* Soft shadow */
            border-radius: 10px;
            /* Rounded corners */
            padding: 24px;
            backdrop-filter: blur(2px);
            /* Adds slight blur effect */
            color: black;
            font-weight: bold;
        }
    </style>
    <script>
        let typingTimer;
        function fetchResults() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                let shopId = document.getElementById("shop_id").value;
                let resultContainer = document.getElementById("result");

                if (shopId.trim() !== "") {
                    let formData = new FormData();
                    formData.append("shop_id", shopId);
                    formData.append("ajax", "1");

                    fetch("track_registration.php", {
                        method: "POST",
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            let html = "";
                            if (data.store && data.owner) {
                                html = `
                                <div class="border-t pt-4">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Registration Details</h2>
                                    <table class="w-full table-auto">
                                        <tr>
                                            <td class="px-4 py-2 text-gray-700 font-semibold">Full Name</td>
                                            <td class="px-4 py-2 text-gray-900">${data.owner.FIRST_NAME} ${data.owner.LAST_NAME}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-gray-700 font-semibold">Shop Name</td>
                                            <td class="px-4 py-2 text-gray-900">${data.store.SHOP_NAME}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-gray-700 font-semibold">Location</td>
                                            <td class="px-4 py-2 text-gray-900">${formatLocation(data.location)}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-gray-700 font-semibold">Store Type</td>
                                            <td class="px-4 py-2 text-gray-900">${data.storeType?.CATEGORY || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-gray-700 font-semibold">Status</td>
                                            <td class="px-4 py-2">
                                                <span class="px-3 py-1 text-white text-sm font-semibold rounded-full 
                                                ${data.owner.STATUS === 'Active' ? 'bg-green-700' : 'bg-yellow-700'}">
                                                    ${data.owner.STATUS}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>`;
                            } else {
                                html = `<div class="mt-6 text-center text-red-600"><p>No data found!</p></div>`;
                            }
                            resultContainer.innerHTML = html;
                        })
                        .catch(error => console.error("Error:", error));
                } else {
                    resultContainer.innerHTML = "";
                }
            }, 700);
        }

        function formatLocation(location) {
            let purok = location?.PUROK ? `Purok - ${location.PUROK}, ` : "";
            let barangay = location?.BARANGAY ? `Brgy. ${location.BARANGAY}, ` : "";
            return `${purok}${barangay}Daraga, Albay`;
        }
    </script>
</head>

<body class="bg-gray-100 py-12 px-4 bg-cover bg-center" style="background-image: url('../../img/bg1.jpg');">


    <!-- Back Button -->
    <div class="max-w-4xl mx-auto mb-4">
        <button onclick="window.location.href='price_monitoring.php'"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </button>
    </div>



    <div class="max-w-4xl mx-auto">
        <div class="transparent-box">

            <h1 class="text-xl font-semibold text-center text-gray-800 mb-6">Track Registration</h1>

            <!-- Track Registration Form -->
            <div class="mb-6 flex">
                <input type="text" id="shop_id" name="shop_id"
                    class="border px-4 py-2 rounded-l-md w-full focus:ring focus:ring-blue-300"
                    placeholder="Enter Reference ID" oninput="fetchResults()" required>

                <!-- Search Button -->
                <button onclick="fetchResults()"
                    class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 flex items-center">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Results Container -->
            <div id="result"></div>
        </div>
    </div>
</body>

</html>