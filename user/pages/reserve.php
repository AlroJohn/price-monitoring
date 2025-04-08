<?php
include('../../includes/connection.php');

// Fetch Product Details, including QTY_STOCK and MEASURE
if (!isset($_GET['shop_id']) || !isset($_GET['product_code'])) {
    echo "<p class='text-red-500 text-center mt-10'>Invalid request.</p>";
    exit();
}

$shop_id = mysqli_real_escape_string($db, $_GET['shop_id']);
$product_code = mysqli_real_escape_string($db, $_GET['product_code']);

$productQuery = "SELECT NAME, PRICE, QTY_STOCK, MEASURE FROM product WHERE SHOP_ID = '$shop_id' AND PRODUCT_CODE = '$product_code'";
$productResult = mysqli_query($db, $productQuery);
$product = mysqli_fetch_assoc($productResult);

if (!$product) {
    echo "<p class='text-red-500 text-center mt-10'>Product not found.</p>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = mysqli_real_escape_string($db, $_POST['customer_name']);
    $contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
    $quantity = (int) $_POST['quantity'];
    $pickup_date = mysqli_real_escape_string($db, $_POST['pickup_date']);

    // Insert the reservation into the database
    $insertQuery = "INSERT INTO reservations (SHOP_ID, PRODUCT_CODE, CUSTOMER_NAME, CONTACT_NUMBER, QUANTITY, PICKUP_DATE)
                    VALUES ('$shop_id', '$product_code', '$customer_name', '$contact_number', '$quantity', '$pickup_date')";

    if (mysqli_query($db, $insertQuery)) {
        echo "<p class='text-green-500 text-center mt-10'>Reservation Successful! Store owner will review your request.</p>";

        // Get Store Owner's Contact Number
        $ownerQuery = "SELECT CONTACT_NUMBER FROM owners WHERE SHOP_ID = '$shop_id'";
        $ownerResult = mysqli_query($db, $ownerQuery);
        $owner = mysqli_fetch_assoc($ownerResult);
        $store_owner_contact = $owner['CONTACT_NUMBER'];

        // Prepare the SMS message
        $message = "New reservation request:\n";
        $message .= "Name: $customer_name\n";
        $message .= "Contact: $contact_number\n";
        $message .= "Quantity: $quantity " . $product['MEASURE'] . "\n";
        $message .= "Total Price: ₱" . number_format($product['PRICE'] * $quantity, 2);

        // Send the SMS to the store owner using Telerivet API
        $api_key = 'your_telerivet_api_key';
        $project_id = 'your_project_id';
        $url = "https://api.telerivet.com/v1/projects/$project_id/messages/send";

        $data = array(
            'to' => $store_owner_contact,
            'content' => $message,
            'api_key' => $api_key
        );

        // Use cURL to send the SMS request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);

        // Optionally handle the response if needed
    } else {
        echo "<p class='text-red-500 text-center mt-10'>Error: " . mysqli_error($db) . "</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="max-w-lg mx-auto my-10 p-6 bg-white rounded-lg shadow-xl">
        <h2 class="text-3xl font-semibold text-gray-800 mb-4">Reserve: <?php echo htmlspecialchars($product['NAME']); ?>
        </h2>

        <div class="mb-6">
            <p class="text-gray-600">Price: <span
                    class="font-semibold text-indigo-600">₱<?php echo number_format($product['PRICE'], 2); ?>/<?php echo $product['MEASURE']; ?></span>
            </p>
            <p class="text-gray-600">Available Stock: <span
                    class="font-semibold text-green-600"><?php echo $product['QTY_STOCK']; ?></span></p>
        </div>

        <form method="POST" class="space-y-4">
            <div>
                <label for="customer_name" class="text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" id="customer_name" name="customer_name" required
                    class="w-full border-2 border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
            </div>

            <div>
                <label for="contact_number" class="text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" required
                    class="w-full border-2 border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
            </div>

            <div>
                <label for="quantity"
                    class="text-sm font-medium text-gray-700"><?php echo $product['MEASURE']; ?></label>
                <input type="number" id="quantity" name="quantity" required min="1"
                    max="<?php echo $product['QTY_STOCK']; ?>"
                    class="w-full border-2 border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out"
                    oninput="updateTotalPrice()">
            </div>

            <p class="text-gray-700 mt-2">Total Price: ₱<span id="totalPrice"
                    class="font-semibold text-indigo-600">0.00</span>/<?php echo $product['MEASURE']; ?></p>

            <div>
                <label for="pickup_date" class="text-sm font-medium text-gray-700">Pickup Date</label>
                <input type="date" id="pickup_date" name="pickup_date" required
                    class="w-full border-2 border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 ease-in-out">
                Confirm Reservation
            </button>
        </form>

        <a href="store.php?shop_id=<?php echo $shop_id; ?>"
            class="block text-center text-indigo-600 mt-6 hover:underline">
            <i class="fas fa-arrow-left"></i> Back to Store
        </a>
    </div>

    <script>
        const price = <?php echo $product['PRICE']; ?>; // PHP value of price

        function updateTotalPrice() {
            const quantity = document.getElementById('quantity').value;
            const totalPrice = price * quantity;
            document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
        }
    </script>

</body>

</html>