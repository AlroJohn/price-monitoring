<?php
session_start(); // Start the session to check for success message

require('../includes/connection.php');

// Get SHOP_ID from URL parameter
if (isset($_GET['shop_id'])) {
    $shop_id = $_GET['shop_id'];

    // Fetch shop details from the stores table
    $storeQuery = "SELECT * FROM stores WHERE SHOP_ID = ?";
    $storeStmt = $db->prepare($storeQuery);
    $storeStmt->bind_param('i', $shop_id);
    $storeStmt->execute();
    $storeResult = $storeStmt->get_result();
    $store = $storeResult->fetch_assoc();

    // Fetch owner details from owners table
    $ownerQuery = "SELECT * FROM owners WHERE SHOP_ID = ?";
    $ownerStmt = $db->prepare($ownerQuery);
    $ownerStmt->bind_param('i', $shop_id);
    $ownerStmt->execute();
    $ownerResult = $ownerStmt->get_result();
    $owner = $ownerResult->fetch_assoc();

    // Fetch location details from location table
    $locationQuery = "SELECT * FROM location WHERE LOCATION_ID = ?";
    $locationStmt = $db->prepare($locationQuery);
    $locationStmt->bind_param('i', $owner['LOCATION_ID']);
    $locationStmt->execute();
    $locationResult = $locationStmt->get_result();
    $location = $locationResult->fetch_assoc();

    // Fetch store type (e.g., Meatshop or other)
    $typeQuery = "SELECT TYPE FROM type WHERE TYPE_ID = ?";
    $typeStmt = $db->prepare($typeQuery);
    $typeStmt->bind_param('i', $store['STORE_ID']);
    $typeStmt->execute();
    $typeResult = $typeStmt->get_result();
    $storeType = $typeResult->fetch_assoc()['TYPE'];
} else {
    // Redirect if no SHOP_ID is provided
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Submitted - Receipt</title>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements@1.0.0-beta.4/dist/js/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/js/all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .copy-btn {
            background-color: #2563EB;
            color: white;
            border-radius: 50%;
            padding: 0.5rem;
            cursor: pointer;
        }

        .copy-btn:hover {
            background-color: #1D4ED8;
        }

        .table-container {
            background-color: #f3f4f6;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .input-copy-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .input-copy-container input {
            flex: 1;
        }
    </style>
</head>

<body class="bg-gray-100 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="table-container">
            <h1 class="text-xl font-semibold text-center text-gray-800 mb-6">Registration Submitted Receipt</h1>
            <table class="min-w-full table-auto">
                <thead class="border-b">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Field</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-gray-700">Full Name</td>
                        <td class="px-4 py-2 text-gray-900">
                            <?php echo $owner['FIRST_NAME'] . ' ' . $owner['LAST_NAME']; ?>
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-gray-700">Location</td>
                        <td class="px-4 py-2 text-gray-900">
                            <?php echo $location['PUROK'] . ', ' . $location['BARANGAY'] . ', Daraga, Albay'; ?>
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-gray-700">Gender</td>
                        <td class="px-4 py-2 text-gray-900"><?php echo $owner['GENDER']; ?></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-gray-700">Email</td>
                        <td class="px-4 py-2 text-gray-900"><?php echo $owner['EMAIL']; ?></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-gray-700">Phone Number</td>
                        <td class="px-4 py-2 text-gray-900"><?php echo $owner['PHONE_NUMBER']; ?></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-gray-700">Shop Name</td>
                        <td class="px-4 py-2 text-gray-900"><?php echo $store['SHOP_NAME']; ?></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-gray-700">Store Type</td>
                        <td class="px-4 py-2 text-gray-900"><?php echo $storeType; ?></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-gray-700 font-semibold">REFERENCE ID</td>
                        <td class="px-4 py-2 text-gray-900 font-semibold">
                            <div class="input-copy-container">
                                <input type="text" id="shop_id" value="<?php echo $shop_id; ?>" readonly
                                    class="border px-4 py-2 rounded-md bg-gray-50 text-lg font-bold w-full">
                                <button onclick="copyShopID()" class="copy-btn mt-2">
                                    <i class="fas fa-copy text-white"></i>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Keep References, use for tracking Registration</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-6">
                <a href="../index.html"
                    class="text-white bg-blue-500 px-6 py-3 rounded-md hover:bg-blue-600">Proceed</a>
            </div>
        </div>
    </div>

    <script>
        // Function to copy SHOP ID to clipboard
        function copyShopID() {
            var copyText = document.getElementById("shop_id");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
            alert("Reference ID copied: " + copyText.value);
        }
    </script>
</body>

</html>