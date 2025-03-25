<?php
include('../../includes/connection.php');

$search = $_GET['query'] ?? '';

if ($search !== '') {
    $query = "SELECT DISTINCT p.* FROM product p 
              JOIN stores s ON p.STORE_ID = s.STORE_ID
              JOIN owners o ON s.SHOP_ID = o.SHOP_ID
              WHERE o.OWNER_ID != 1 
              AND o.STATUS = 'Active'
              AND p.NAME LIKE '%$search%'";

    $result = mysqli_query($db, $query);
    $output = "";

    if (mysqli_num_rows($result) > 0) {
        while ($product = mysqli_fetch_assoc($result)) {
            // Product Image Handling
            $prodImg = (!empty($product['IMAGE']) && file_exists("../../assets/product_img/{$product['IMAGE']}"))
                ? "../../assets/product_img/{$product['IMAGE']}"
                : "../../assets/product_img/no_img.jpg";

            $output .= "<div class='bg-gray-100 p-3 shadow-md rounded-lg text-center'>
                    <img src='$prodImg' alt='Product Image' class='h-28 w-full object-cover mb-2 rounded'>
                    <h3 class='text-sm font-medium'>{$product['NAME']}</h3>
                    <p class='text-blue-500 font-semibold text-sm'>₱{$product['PRICE']}</p>
                    <div class='mt-2 flex justify-center space-x-2'>
                        <a href='product_details.php?shop_id={$product['SHOP_ID']}&product_code={$product['PRODUCT_CODE']}' class='bg-blue-500 text-white text-xs px-2 py-1 rounded'>Details</a>
                        <a href='reserve_index.php?shop_id={$product['SHOP_ID']}&product_code={$product['PRODUCT_CODE']}' class='bg-green-500 text-white text-xs px-2 py-1 rounded'>Reserve</a>
                    </div>
                </div>";
        }
    } else {
        $output = "<p class='text-gray-500 text-center col-span-full text-sm'>No Products Found!</p>";
    }

    echo $output;
}
?>