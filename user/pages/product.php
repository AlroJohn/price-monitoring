<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <style>
        /* Futuristic Button Hover Effect */
        .futuristic-button {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .futuristic-button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body class="bg-gray-800 text-white font-sans">
    <!-- Include Topbar -->
    <?php include '../../user/include/topbar.php'; ?>

    <!-- Category Filter Section -->
    <section class="mt-6">
        <div class="container mx-auto text-center space-y-4">
            <h2 class="text-2xl font-bold text-blue-400">Browse Products by Category</h2>
            <div class="flex justify-between space-x-4 items-center">
                <button class="px-6 py-3 rounded-full futuristic-button text-white font-medium flex-1"
                    onclick="filterProducts('All')">
                    All
                </button>
                <button class="px-6 py-3 rounded-full futuristic-button text-white font-medium flex-1"
                    onclick="filterProducts('Meatshop')">
                    Meatshop
                </button>
                <button class="px-6 py-3 rounded-full futuristic-button text-white font-medium flex-1"
                    onclick="filterProducts('Animal Feeds')">
                    Animal Feeds
                </button>
                <button class="px-6 py-3 rounded-full futuristic-button text-white font-medium flex-1"
                    onclick="filterProducts('Hardware')">
                    Hardware
                </button>
                <button class="px-6 py-3 rounded-full futuristic-button text-white font-medium flex-1"
                    onclick="filterProducts('General Merchandise')">
                    Gen. Merchandise
                </button>

                <!-- Search Bar -->
                <div class="flex items-center space-x-2">
                    <input type="text" id="search-bar"
                        class="px-4 py-2 rounded-full bg-gray-700 text-white placeholder-gray-400"
                        placeholder="Search products..." onkeyup="searchProducts()">
                    <!--<button class="px-4 py-2 bg-blue-500 rounded-full text-white" onclick="searchProducts()">
                        <i class="fas fa-search"></i>
                    </button>-->
                </div>
            </div>
        </div>
    </section>

    <!-- Product List Section -->
    <section class="py-12">
        <div class="container mx-auto">
            <div class="grid gap-4 grid-cols-2 sm:grid-cols-4 lg:grid-cols-7" id="product-list">
                <?php
                include '../../includes/connection.php';

                // Modify the SQL query to group products by PRODUCT_CODE and STORE_ID
                $query = "SELECT p.*, st.CATEGORY FROM product p
                          JOIN store_type st ON p.STORE_ID = st.STORE_ID
                          WHERE p.pro_status = 'active'
                          GROUP BY p.PRODUCT_CODE, p.STORE_ID";
                $result = mysqli_query($db, $query) or die(mysqli_error($db));

                $category_map = [
                    1 => "Meatshop",
                    2 => "Animal Feeds",
                    3 => "Hardware",
                    4 => "General Merchandise"
                ];

                // Grouping products by PRODUCT_CODE and STORE_ID
                $grouped_products = [];
                while ($product = mysqli_fetch_assoc($result)) {
                    $product_image = !empty($product['IMAGE_URL']) ? $product['IMAGE_URL'] : '../product_img/default.jpg';
                    $product_category = $category_map[$product['STORE_ID']] ?? 'Unknown';
                    $product_key = $product['PRODUCT_CODE'] . '-' . $product['STORE_ID']; // Group by PRODUCT_CODE and STORE_ID
                
                    // Store product under its group key
                    if (!isset($grouped_products[$product_key])) {
                        $grouped_products[$product_key] = [];
                    }
                    $grouped_products[$product_key][] = $product;
                }

                // Now, display the products grouped by PRODUCT_CODE and STORE_ID
                foreach ($grouped_products as $group_key => $products) {
                    echo '<div class="product-group">';
                    foreach ($products as $product) {
                        $product_image = !empty($product['IMAGE_URL']) ? $product['IMAGE_URL'] : '../product_img/default.jpg';
                        $product_category = $category_map[$product['STORE_ID']] ?? 'Unknown';

                        echo '<div class="bg-gray-900 shadow-lg border border-gray-600 rounded-lg p-4 text-center product-card" data-category="' . $product_category . '">';
                        echo '<img class="w-full h-40 object-cover rounded-md transition-transform duration-500 transform hover:scale-105" src="' . $product_image . '" alt="' . $product['NAME'] . '">';
                        echo '<h5 class="text-lg font-semibold mt-4 truncate text-blue-400">' . $product['NAME'] . '</h5>';
                        echo '<p class="text-gray-400 text-sm mt-2">₱ ' . number_format($product['PRICE'], 2) . '</p>';
                        echo '<a href="product_detail.php?id=' . $product['PRODUCT_ID'] . '" class="mt-4 inline-block px-6 py-2 bg-blue-500 text-white rounded-lg font-medium text-xs hover:bg-blue-600 transition-all">View</a>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6 mt-12">
        <p class="text-center text-sm">&copy; 2025 Price Monitoring System. All Rights Reserved. | <a href="#"
                class="underline hover:no-underline">Privacy Policy</a></p>
    </footer>

    <!-- Filter Products Script -->
    <script>
        function filterProducts(category) {
            const products = document.querySelectorAll('.product-card');
            const buttons = document.querySelectorAll('.futuristic-button');

            // Remove active styles from all buttons
            buttons.forEach(button => button.classList.remove('bg-blue-600'));

            // Add active styles to the clicked button
            event.target.classList.add('bg-blue-600');

            // Filter products based on category
            products.forEach(product => {
                const productCategory = product.getAttribute('data-category');
                product.style.display = (category === 'All' || productCategory === category) ? 'block' : 'none';
            });
        }

        // Search Products Script
        function searchProducts() {
            const searchInput = document.getElementById('search-bar').value.toLowerCase();
            const products = document.querySelectorAll('.product-card');

            products.forEach(product => {
                const productName = product.querySelector('h5').textContent.toLowerCase();
                if (productName.includes(searchInput)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>