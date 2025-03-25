<?php
include('../../includes/connection.php');

if (!isset($_GET['shop_id']) || !isset($_GET['product_code'])) {
    echo "<p class='text-red-500 text-center mt-10'>Invalid request.</p>";
    exit();
}

$shop_id = mysqli_real_escape_string($db, $_GET['shop_id']);
$product_code = mysqli_real_escape_string($db, $_GET['product_code']);

// Fetch Product Details
$productQuery = "SELECT NAME, PRICE FROM product WHERE SHOP_ID = '$shop_id' AND PRODUCT_CODE = '$product_code'";
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

    $insertQuery = "INSERT INTO reservations (SHOP_ID, PRODUCT_CODE, CUSTOMER_NAME, CONTACT_NUMBER, QUANTITY, PICKUP_DATE)
                    VALUES ('$shop_id', '$product_code', '$customer_name', '$contact_number', '$quantity', '$pickup_date')";

    if (mysqli_query($db, $insertQuery)) {
        echo "<p class='text-green-500 text-center mt-10'>Reservation Successful! Store owner will review your request.</p>";
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
    <div class="max-w-lg mx-auto my-10 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold text-gray-800">Reserve: <?php echo htmlspecialchars($product['NAME']); ?></h2>
        <p class="text-gray-700 mt-1">Price: ₱<?php echo number_format($product['PRICE'], 2); ?></p>

        <form method="POST" class="mt-4">
            <label class="block text-gray-700">Full Name</label>
            <input type="text" name="customer_name" required class="w-full border p-2 rounded-md">

            <label class="block text-gray-700 mt-2">Contact Number</label>
            <input type="text" name="contact_number" required class="w-full border p-2 rounded-md">

            <label class="block text-gray-700 mt-2">Quantity</label>
            <input type="number" name="quantity" required min="1" class="w-full border p-2 rounded-md">

            <label class="block text-gray-700 mt-2">Pickup Date</label>
            <input type="date" name="pickup_date" required class="w-full border p-2 rounded-md">

            <button type="submit"
                class="w-full bg-green-500 text-white px-4 py-2 mt-4 rounded-md shadow hover:bg-green-600">
                Confirm Reservation
            </button>
        </form>

        <a href="store.php?shop_id=<?php echo $shop_id; ?>"
            class="block text-center text-gray-600 mt-4 hover:underline">
            <i class="fas fa-arrow-left"></i> Back to Store
        </a>
    </div>
</body>

</html>