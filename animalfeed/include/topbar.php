<?php
include('../../includes/connection.php');

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

$min_date = date('Y-m-d');
$max_date = date('Y-m-d', strtotime('+5 days'));

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Separate name fields for customer
    $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($db, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($db, $_POST['last_name']);

    // Concatenate first name, middle initial, and last name
    $middle_initial = strtoupper(substr($middle_name, 0, 1)) . '.';
    $customer_name = $first_name . ' ' . $middle_initial . ' ' . $last_name;

    $contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
    $quantity = (int) $_POST['quantity'];
    $pickup_date = mysqli_real_escape_string($db, $_POST['pickup_date']);
    $calculated_price = mysqli_real_escape_string($db, $_POST['calculated_price']);

    // Validate pickup date
    if ($pickup_date < $min_date || $pickup_date > $max_date) {
        echo "<p class='text-red-500 text-center mt-10'>Invalid pickup date selected.</p>";
        exit();
    }

    $insertQuery = "INSERT INTO reservations (SHOP_ID, PRODUCT_CODE, CUSTOMER_NAME, CONTACT_NUMBER, QUANTITY, PICKUP_DATE, CALCULATED_PRICE)
                    VALUES ('$shop_id', '$product_code', '$customer_name', '$contact_number', '$quantity', '$pickup_date', '$calculated_price')";

    if (mysqli_query($db, $insertQuery)) {
        // Redirect using PRG to prevent duplicate submissions
        header("Location: " . $_SERVER['PHP_SELF'] . "?shop_id=$shop_id&product_code=$product_code&success=1");
        exit();

        // After reservation, send SMS to store owner

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

<body class="bg-gray-100 font-sans flex justify-center items-center h-screen">

    <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-xl">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            Product name: <?php echo htmlspecialchars($product['NAME']); ?>
        </h2>

        <div class="mb-6">
            <p class="text-gray-600">Price: <span
                    class="font-semibold text-indigo-600">₱<?php echo number_format($product['PRICE'], 2); ?>/<?php echo $product['MEASURE']; ?></span>
            </p>
            <p class="text-gray-600">Available Stock: <span
                    class="font-semibold text-green-600"><?php echo $product['QTY_STOCK']; ?></span></p>
        </div>

        <?php if (isset($_GET['success'])): ?>
                <!-- Success Notification -->
                <p class="text-green-500 text-center mt-10">Reservation Successful! Store owner will review your request.</p>
                <script>
                    // After 5 seconds, redirect to homepage
                    setTimeout(function () {
                        window.location.href = "price_monitoring.php";
                    }, 5000);
                </script>
        <?php else: ?>
                <!-- Reservation Form -->
                <form method="POST" class="space-y-4">
                    <!-- Separate Name Fields -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="first_name" class="text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="first_name" name="first_name" required placeholder="First Name"
                                class="w-full border-2 border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="middle_name" class="text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" required placeholder="Middle Name"
                                class="w-full border-2 border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="last_name" class="text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required placeholder="Last Name"
                                class="w-full border-2 border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                        </div>
                    </div>

                    <div>
                        <label for="contact_number" class="text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" required placeholder="Contact Number"
                            class="w-full border-2 border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                    </div>

                    <div class="flex flex-row gap-4 items-center">
                        <div class="flex-1">
                            <label for="quantity"
                                class="text-sm font-medium text-gray-700"><?php echo $product['MEASURE']; ?></label>
                            <input type="number" id="quantity" name="quantity" required min="1" placeholder="Quantity"
                                class="w-full border-2 border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out"
                                oninput="updateTotalPrice()">
                        </div>

                        <div class="flex-none">
                            <label for="fraction" class="text-sm font-medium text-gray-700">Fraction</label>
                            <select id="fraction" name="fraction" required
                                class="w-full border-2 border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out"
                                onchange="updateTotalPrice()">
                                <option value="1">Full</option>
                                <option value="0.5">1/2</option>
                                <option value="0.25">1/4</option>
                                <option value="0.125">1/8</option>
                                <option value="0.0625">1/16</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="calculated_price" class="text-sm font-medium text-gray-700">Total Price</label>
                        <input type="text" id="calculated_price" name="calculated_price" readonly placeholder="Total Price"
                            class="w-full border-2 border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                    </div>

                    <div>
                        <label for="pickup_date" class="text-sm font-medium text-gray-700">Pickup Date
                            <span class="font-thin text-xs">(Pickup date is only 5 days from now)</span>
                        </label>
                        <input type="date" id="pickup_date" name="pickup_date" required placeholder="Pickup Date"
                            min="<?php echo $min_date; ?>" max="<?php echo $max_date; ?>"
                            class="w-full border-2 border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 ease-in-out">
                        Confirm Reservation
                    </button>
                </form>
        <?php endif; ?>

        <a href="javascript:history.back()" class="block text-center text-indigo-600 mt-6 hover:underline">
            <i class="fas fa-arrow-left"></i> Back to Store
        </a>
    </div>

    <script>
        const price = <?php echo $product['PRICE']; ?>;

        function updateTotalPrice() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const fraction = parseFloat(document.getElementById('fraction').value) || 1;
            const totalPrice = price * quantity * fraction;
            document.getElementById('calculated_price').value = totalPrice.toFixed(2);
        }

        // Client-side validation for pickup_date
        document.getElementById('pickup_date').addEventListener('change', function () {
            const minDate = new Date(this.min);
            const maxDate = new Date(this.max);
            const selectedDate = new Date(this.value);
            if (selectedDate < minDate || selectedDate > maxDate) {
                alert('Please select a pickup date between ' + this.min + ' and ' + this.max + '.');
                this.value = '';
            }
        });
    </script>

</body>

</html>
