<?php
// Include database connection
include('../../includes/connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Monitoring System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
    .truncate-name {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Show only 2 lines */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }
</style>

<body class="font-sans bg-gray-100">

    <!-- Include navigation topbars -->
    <?php include '../../user/includes/topbar.php'; ?>
    <?php include '../../user/includes/topbar2.php'; ?>

    <div id="mainContent">
        <!-- Store Categories Section -->
        <section id="storeCategories" class="container mx-auto my-3 px-4 bg-primary">
            <h2 class="text-2xl font-bold mb-4 text-center">Store Category</h2>
            <div class="flex justify-center space-x-4 border-b pb-2 mb-6">
                <!-- Default 'All' Category -->
                <a href="price_monitoring.php?category=All" class="category-tab px-6 py-2 text-gray-600 hover:text-blue-500"
                    data-category="All">All</a>

                <!-- Dynamically Load Store Categories Except 'General Merchandise' -->
                <?php
                $catQuery = "SELECT DISTINCT CATEGORY FROM store_type WHERE CATEGORY != 'General Merchandise'";
                $catResult = mysqli_query($db, $catQuery);
                while ($cat = mysqli_fetch_assoc($catResult)) {
                    echo "<a href='price_monitoring.php?category={$cat['CATEGORY']}' class='category-tab px-6 py-2 text-gray-600 hover:text-blue-500' data-category='{$cat['CATEGORY']}'>{$cat['CATEGORY']}</a>";
                }
                ?>
            </div>

            <!-- Store Listings -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-10 gap-4">
                <?php
                // Get category from URL, default to 'All'
                $category = $_GET['category'] ?? 'All';

                // Query stores where OWNER is active and not admin
                $storeQuery = ($category == 'All') ?
                    "SELECT s.* FROM stores s 
                     JOIN owners o ON s.SHOP_ID = o.SHOP_ID 
                     WHERE o.OWNER_ID != 1 AND o.STATUS = 'Active'"
                    :
                    "SELECT s.* FROM stores s 
                     JOIN owners o ON s.SHOP_ID = o.SHOP_ID 
                     JOIN store_type st ON s.STORE_ID = st.STORE_ID 
                     WHERE st.CATEGORY = '$category' AND o.OWNER_ID != 1 AND o.STATUS = 'Active'";

                $storeResult = mysqli_query($db, $storeQuery);

                if (mysqli_num_rows($storeResult) > 0) {
                    while ($store = mysqli_fetch_assoc($storeResult)) {
                        // Determine store image
                        $storeImg = "../../assets/store_img/{$store['IMAGE']}";
                        if (!file_exists($storeImg) || empty($store['IMAGE'])) {
                            $storeImg = "../../assets/store_img/no_img.jpg";
                        }

                        // Store card with link using SHOP_ID
                        echo "<a href='store.php?shop_id={$store['SHOP_ID']}' class='block bg-white p-4 shadow-lg rounded-lg text-center hover:bg-gray-200'>
                                <img src='$storeImg' alt='Store Image' class='h-24 w-full object-cover rounded mb-2'>
                                <h3 class='text-sm font-semibold'>{$store['SHOP_NAME']}</h3>
                              </a>";
                    }
                } else {
                    echo "<p class='text-gray-500 text-center col-span-full'>No Stores Available!</p>";
                }
                ?>
            </div>
        </section>

        <!-- Product Listings Section -->
        <section class="container mx-auto my-10 px-4">
            <div class="bg-blue-500 p-6 rounded-lg">
                <h1 class="text-2xl font-bold text-white mb-4 text-center">PRODUCTS</h1>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <!-- Search Field -->
                    <div class="mb-6">
                        <div class="relative max-w-md mx-auto">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" id="productSearch" placeholder="Search products by name or price..." 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div id="productSection" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <?php
                        // Get category from URL, default to 'All'
                        $category = $_GET['category'] ?? 'All';

                        // Base product query
                        $prodQuery = "SELECT DISTINCT p.*, st.CATEGORY 
                                     FROM product p 
                                     JOIN stores s ON p.STORE_ID = s.STORE_ID
                                     JOIN owners o ON s.SHOP_ID = o.SHOP_ID
                                     JOIN store_type st ON s.STORE_ID = st.STORE_ID
                                     WHERE o.OWNER_ID != 1 AND o.STATUS = 'Active'";

                        // Add category filter if not 'All'
                        if ($category != 'All') {
                            $prodQuery .= " AND st.CATEGORY = '$category'";
                        }

                        $prodResult = mysqli_query($db, $prodQuery);

                        if (mysqli_num_rows($prodResult) > 0) {
                            while ($product = mysqli_fetch_assoc($prodResult)) {
                                $prodImg = (!empty($product['IMAGE']) && file_exists("../../assets/product_img/{$product['IMAGE']}"))
                                    ? "../../assets/product_img/{$product['IMAGE']}"
                                    : "../../assets/product_img/no_img.jpg";

                                echo "<div class='product-item bg-gray-100 p-3 shadow-md rounded-lg text-center flex flex-col justify-between h-full' 
                                          data-name='".strtolower(htmlspecialchars($product['NAME']))."'
                                          data-price='{$product['PRICE']}'
                                          data-measure='".strtolower(htmlspecialchars($product['MEASURE']))."'
                                          data-category='".strtolower(htmlspecialchars($product['CATEGORY']))."'>
                                        <img src='$prodImg' alt='Product Image' class='h-28 w-full object-cover mb-2 rounded'>
                                        <h3 class='product-name text-sm font-medium truncate-name flex-grow'>{$product['NAME']}</h3>
                                        <h3 class='product-price text-sm font-medium truncate-name flex-grow'>&#8369;{$product['PRICE']}/{$product['MEASURE']}</h3>
                                        <div class='mt-2 flex justify-center space-x-2'>
                                            <a href='product_details.php?shop_id={$product['SHOP_ID']}&product_code={$product['PRODUCT_CODE']}' class='bg-blue-500 text-white text-xs px-2 py-1 rounded hover:bg-blue-600 transition'>Details</a>
                                            <a href='reserve_index.php?shop_id={$product['SHOP_ID']}&product_code={$product['PRODUCT_CODE']}' class='bg-green-500 text-white text-xs px-2 py-1 rounded hover:bg-green-600 transition'>Reserve</a>
                                        </div>
                                        <span class='mt-2 text-xs text-gray-500'>{$product['CATEGORY']}</span>
                                    </div>";
                            }
                        } else {
                            echo "<p class='text-gray-500 text-center col-span-full text-sm'>No Products Available!</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Highlight Active Category in Navigation
        document.addEventListener("DOMContentLoaded", function () {
            let params = new URLSearchParams(window.location.search);
            let currentCategory = params.get("category") || "All";
            document.querySelectorAll(".category-tab").forEach(tab => {
                if (tab.dataset.category === currentCategory) {
                    tab.classList.add("text-blue-500", "font-bold", "border-b-2", "border-blue-500");
                }
            });

            // Product Search Functionality
            const productSearch = document.getElementById('productSearch');
            productSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const productItems = document.querySelectorAll('.product-item');
                let hasVisibleItems = false;
                
                productItems.forEach(item => {
                    const productName = item.dataset.name;
                    const productPrice = item.dataset.price;
                    const productMeasure = item.dataset.measure;
                    const productCategory = item.dataset.category;
                    
                    // Search in name, price, and measure
                    if (productName.includes(searchTerm) || 
                        productPrice.includes(searchTerm) || 
                        productMeasure.includes(searchTerm)) {
                        // Also check if it matches the current category filter
                        const currentCategory = new URLSearchParams(window.location.search).get('category') || 'All';
                        if (currentCategory === 'All' || productCategory === currentCategory.toLowerCase()) {
                            item.style.display = 'flex';
                            hasVisibleItems = true;
                        } else {
                            item.style.display = 'none';
                        }
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Show message if no results found
                const noResultsMsg = document.querySelector('.no-results-message');
                
                if (!hasVisibleItems) {
                    if (!noResultsMsg) {
                        const msg = document.createElement('p');
                        msg.className = 'text-gray-500 text-center col-span-full text-sm no-results-message';
                        msg.textContent = 'No products found matching your search.';
                        document.getElementById('productSection').appendChild(msg);
                    }
                } else {
                    if (noResultsMsg) {
                        noResultsMsg.remove();
                    }
                }
            });

            // Clear search when category changes
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    productSearch.value = '';
                    // The page will reload with the new category filter
                });
            });
        });
    </script>
</body>
</html>