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
                <a href="index.php?category=All" class="category-tab px-6 py-2 text-gray-600 hover:text-blue-500"
                    data-category="All">All</a>

                <!-- Dynamically Load Store Categories Except 'General Merchandise' -->
                <?php
                $catQuery = "SELECT DISTINCT CATEGORY FROM store_type WHERE CATEGORY != 'General Merchandise'";
                $catResult = mysqli_query($db, $catQuery);
                while ($cat = mysqli_fetch_assoc($catResult)) {
                    echo "<a href='index.php?category={$cat['CATEGORY']}' class='category-tab px-6 py-2 text-gray-600 hover:text-blue-500' data-category='{$cat['CATEGORY']}'>{$cat['CATEGORY']}</a>";
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
                    <div id="productSection" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <?php
                        $prodQuery = "SELECT DISTINCT p.* FROM product p 
                            JOIN stores s ON p.STORE_ID = s.STORE_ID
                            JOIN owners o ON s.SHOP_ID = o.SHOP_ID
                            WHERE o.OWNER_ID != 1 AND o.STATUS = 'Active'";

                        $prodResult = mysqli_query($db, $prodQuery);

                        if (mysqli_num_rows($prodResult) > 0) {
                            while ($product = mysqli_fetch_assoc($prodResult)) {
                                $prodImg = (!empty($product['IMAGE']) && file_exists("../../assets/product_img/{$product['IMAGE']}"))
                                    ? "../../assets/product_img/{$product['IMAGE']}"
                                    : "../../assets/product_img/no_img.jpg";

                                echo "<div class='bg-gray-100 p-3 shadow-md rounded-lg text-center flex flex-col justify-between h-full'>
                                <img src='$prodImg' alt='Product Image' class='h-28 w-full object-cover mb-2 rounded'>
                                <h3 class='text-sm font-medium truncate-name flex-grow'>{$product['NAME']}</h3>
                                <div class='mt-2 flex justify-center space-x-2'>
                                    <a href='product_details.php?shop_id={$product['SHOP_ID']}&product_code={$product['PRODUCT_CODE']}' class='bg-blue-500 text-white text-xs px-2 py-1 rounded'>Details</a>
                                    <a href='reserve_index.php?shop_id={$product['SHOP_ID']}&product_code={$product['PRODUCT_CODE']}' class='bg-green-500 text-white text-xs px-2 py-1 rounded'>Reserve</a>
                                </div>
                            </div>
                            ";
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
        });
    </script>

</body>

</html>