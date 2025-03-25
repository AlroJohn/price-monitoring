<?php
// Start session to access session variables
session_start();

// Include database connection
include '../../includes/connection.php';

// Check if the session variable for SHOP_ID is set
if (!isset($_SESSION['SHOP_ID'])) {
    // Redirect to login page if SHOP_ID is not set in the session
    header('Location: login.php');
    exit();
}

// Fetch SHOP_ID and STORE_ID from session (logged-in user)
$shop_id = $_SESSION['SHOP_ID']; // Assuming SHOP_ID is stored in session
$store_id = $_SESSION['STORE_ID']; // Assuming STORE_ID is stored in session

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_code = mysqli_real_escape_string($db, $_POST['product_code']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $qty_stock = mysqli_real_escape_string($db, $_POST['qty_stock']);
    $measure = mysqli_real_escape_string($db, $_POST['measure']);
    $price = mysqli_real_escape_string($db, $_POST['price']);
    $date_stock_in = mysqli_real_escape_string($db, $_POST['date_stock_in']);
    $date_expiry = mysqli_real_escape_string($db, $_POST['date_expiry']);

    // Check if the product already exists
    $check_query = "SELECT * FROM product WHERE SHOP_ID = '$shop_id' AND PRODUCT_CODE = '$product_code' AND DATE_STOCK_IN = '$date_stock_in' AND DATE_EXPIRY = '$date_expiry' AND MEASURE = '$measure' AND PRICE = '$price'";

    $check_result = mysqli_query($db, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Product already exists, update the quantity
        $existing_product = mysqli_fetch_assoc($check_result);
        $new_qty_stock = $existing_product['QTY_STOCK'] + $qty_stock;

        $update_query = "UPDATE product 
                         SET QTY_STOCK = '$new_qty_stock'
                         WHERE PRODUCT_ID = '{$existing_product['PRODUCT_ID']}'";

        if (mysqli_query($db, $update_query)) {
            // Redirect with success flag after updating
            header("Location: products.php?success=true");
            exit();
        } else {
            echo "Error: " . mysqli_error($db);
        }
    } else {
        // Handle Image Upload
        $image_path = null;

        // Check if an image was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = $_FILES['image']['name'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_size = $_FILES['image']['size'];
            $image_error = $_FILES['image']['error'];

            // Get the extension of the image file
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            // Define allowed file types
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            // Check if the image has an allowed extension
            if (in_array($image_ext, $allowed_extensions)) {
                // Generate the new image name (NAME + SHOP_ID + PRODUCT_ID)
                $image_name = $name . $shop_id . time() . '.' . $image_ext;  // Adding time() to avoid name collisions
                $image_path = "../../assets/product_img/" . $image_name;

                // Move the uploaded image to the desired folder
                if (move_uploaded_file($image_tmp_name, $image_path)) {
                    // Image upload success
                } else {
                    echo "Error uploading the image.";
                    exit();
                }
            } else {
                echo "Invalid image type. Only jpg, jpeg, png, gif are allowed.";
                exit();
            }
        }

        // Insert the new product with or without image
        $query = "INSERT INTO product (SHOP_ID, STORE_ID, PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, MEASURE, PRICE, DATE_STOCK_IN, DATE_EXPIRY, pro_status, IMAGE)
                  VALUES ('$shop_id', '$store_id', '$product_code', '$name', '$description', '$qty_stock', '$measure', '$price', '$date_stock_in', '$date_expiry', 'active', '$image_path')";

        if (mysqli_query($db, $query)) {
            // Redirect with success flag after insertion
            header("Location: products.php?success=true");
            exit();
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}


// Fetch products for the current shop
$query = "SELECT * FROM product WHERE SHOP_ID = ? AND pro_status = 'active'";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $shop_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <style>
        /* Set background image */
        body {
            background-image: url('../../img/meatshop_bg.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            /* Ensure the image covers the whole screen */
            color: white;
            /* Make text white for better visibility */
        }

        /* Optional: Add a dark overlay to make text more readable over the background */
        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
            /* Dark overlay for text readability */
            padding: 20px;
            /* Add some padding */
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Table header */
        th {
            background-color: #d53f8c;
            /* A soft, meat-related tone */
            color: white;
            padding: 10px;
            text-align: left;
        }

        /* Table rows */
        td {
            background-color: rgba(255, 255, 255, 0.2);
            /* Light translucent background */
            padding: 10px;
            border-bottom: 1px solid #fff;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.3);
            /* Light highlight on hover */
        }

        /* Add button styling */
        .add-product-btn {
            background-color: #e53e3e;
            /* A meat-related accent color */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .add-product-btn:hover {
            background-color: #c53030;
            /* Darken on hover */
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 500px;
        }

        .modal.show {
            display: flex;
        }

        /* Notification Style */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: none;
            z-index: 100;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                right: -300px;
            }

            to {
                right: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Notification Div -->
    <div id="successNotification" class="notification">
        Product successfully added!
    </div>


    <!-- Include Top Bar -->
    <?php include '../include/topbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 py-6 ">
        <!-- Add Product Button -->
        <div class="flex justify-end mt-4">
            <button id="openModal" class="add-product-btn flex items-center space-x-2">
                <i class="ph ph-plus text-lg"></i>
                <span>Add Product</span>
            </button>
        </div>

        <!-- Products Table -->
        <div class="bg-white mt-6 p-6 rounded-lg shadow-md overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th class="p-3 text-left">Product Name</th>
                        <th class="p-3 text-left">Description</th>
                        <th class="p-3 text-left">Stock</th>
                        <th class="p-3 text-left">Date Stock In</th>
                        <th class="p-3 text-left">Date Expiry</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='p-3'>" . htmlspecialchars($row['NAME']) . "</td>";
                            echo "<td class='p-3'>" . htmlspecialchars($row['DESCRIPTION']) . "</td>";
                            echo "<td class='p-3'>" . $row['QTY_STOCK'] . " " . $row['MEASURE'] . "</td>";
                            echo "<td class='p-3'>" . date('Y-m-d', strtotime($row['DATE_STOCK_IN'])) . "</td>";
                            echo "<td class='p-3'>" . date('Y-m-d', strtotime($row['DATE_EXPIRY'])) . "</td>";
                            echo "<td class='p-3 flex justify-center space-x-4'>";
                            echo "<a href='product_edit.php?id=" . $row['PRODUCT_ID'] . "' class='text-blue-600 hover:text-blue-800' title='Edit Product'>
                                <i class='fas fa-edit text-lg'></i></a>";
                            echo "<a href='product_delete.php?id=" . $row['PRODUCT_ID'] . "' class='text-red-600 hover:text-red-800' title='Delete Product'>
                                <i class='fas fa-trash text-lg'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center p-4 text-xl font-semibold text-red-600'>No Product Available!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="productModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="modal-content bg-white p-6 rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Add Product</h2>
            <form action="products.php" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Product Code -->
                    <div>
                        <label for="product_code" class="block text-gray-700">Product Code</label>
                        <input type="text" id="product_code" name="product_code"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                            required placeholder="Enter product code" />
                    </div>
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-gray-700">Name</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                            required placeholder="Enter product name" />
                    </div>
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-gray-700">Description</label>
                        <textarea id="description" name="description"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                            required placeholder="Enter product description"></textarea>
                    </div>
                    <!-- Stock Quantity -->
                    <div>
                        <label for="qty_stock" class="block text-gray-700">Stock Quantity</label>
                        <input type="number" id="qty_stock" name="qty_stock"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                            required placeholder="Enter stock quantity" />
                    </div>
                    <!-- Measure (Select) -->
                    <div>
                        <label for="measure" class="block text-gray-700">Measure (e.g., kilo, pack, whole)</label>
                        <select id="measure" name="measure"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                            required>
                            <option value="kilo">Kilo</option>
                            <option value="pack">Pack</option>
                            <option value="whole">Whole</option>
                        </select>
                    </div>
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-gray-700">Price</label>
                        <input type="number" id="price" name="price"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                            required placeholder="Enter product price" step="0.01" min="0" />
                    </div>
                    <!-- Stock In Date -->
                    <div>
                        <label for="date_stock_in" class="block text-gray-700">Stock In Date</label>
                        <input type="date" id="date_stock_in" name="date_stock_in"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                            required />
                    </div>
                    <!-- Expiry Date -->
                    <div>
                        <label for="date_expiry" class="block text-gray-700">Expiry Date</label>
                        <input type="date" id="date_expiry" name="date_expiry"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                            required />
                    </div>
                    <!-- Product Image (Optional) -->
                    <div>
                        <label for="image" class="block text-gray-700">Product Image (Optional)</label>
                        <input type="file" id="image" name="image"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700" />
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        // Show modal when Add Product button is clicked
        document.getElementById('openModal').addEventListener('click', function () {
            document.getElementById('productModal').classList.add('show');
        });

        // Close modal when clicking outside the modal content
        window.addEventListener('click', function (event) {
            if (event.target === document.getElementById('productModal')) {
                document.getElementById('productModal').classList.remove('show');
            }
        });

        // Function to show the success notification
        function showSuccessNotification() {
            var notification = document.getElementById('successNotification');
            notification.style.display = 'block'; // Show the notification
            setTimeout(function () {
                notification.style.display = 'none'; // Hide the notification after 3 seconds
            }, 3000);
        }

        // Check if the form was submitted successfully
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true') { ?>
            showSuccessNotification(); // Show notification if product added successfully
        <?php } ?>

    </script>
</body>

</html>