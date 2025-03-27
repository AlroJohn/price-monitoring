<?php
include '../includes/connection.php';

$category = 'Hardware'; // Change this for other store categories

$query = "SELECT * FROM stores 
          INNER JOIN store_type ON stores.STORE_ID = store_type.STORE_ID 
          WHERE store_type.CATEGORY = '$category' 
          AND stores.SHOP_ID != 0";

$result = mysqli_query($db, $query);

// Handle delete operation - Delete from multiple related tables
if (isset($_POST['delete_store']) && isset($_POST['shop_id'])) {
    $shop_id = $_POST['shop_id'];

    // Begin transaction to ensure all operations complete or none
    mysqli_begin_transaction($db);

    try {
        // Delete from products table
        $delete_products = "DELETE FROM product WHERE SHOP_ID = $shop_id";
        mysqli_query($db, $delete_products);

        // Delete from owners table
        $delete_owner = "DELETE FROM owners WHERE SHOP_ID = $shop_id";
        mysqli_query($db, $delete_owner);

        // Delete from users table related to this shop
        $delete_users = "DELETE FROM users WHERE SHOP_ID = $shop_id";
        mysqli_query($db, $delete_users);

        // Delete from store_type table
        $delete_store_type = "DELETE FROM store_type WHERE STORE_ID = (SELECT STORE_ID FROM stores WHERE SHOP_ID = $shop_id)";
        mysqli_query($db, $delete_store_type);

        // Finally delete from stores table
        $delete_store = "DELETE FROM stores WHERE SHOP_ID = $shop_id";
        mysqli_query($db, $delete_store);

        // Commit the transaction
        mysqli_commit($db);

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if any query fails
        mysqli_rollback($db);
        echo "Delete failed: " . $e->getMessage();
    }
}

// Handle edit operation
if (isset($_POST['edit_store']) && isset($_POST['shop_id'])) {
    $shop_id = $_POST['shop_id'];
    $shop_name = mysqli_real_escape_string($db, $_POST['shop_name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $availability = mysqli_real_escape_string($db, $_POST['availability']);

    $update_query = "UPDATE stores SET 
                    SHOP_NAME = '$shop_name', 
                    ADDRESS = '$address', 
                    AVAILABILITY = '$availability' 
                    WHERE SHOP_ID = $shop_id";

    if (mysqli_query($db, $update_query)) {
        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

include('../includes/sidebar.php'); // Sidebar for navigation
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $category ?> Stores</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- PhosphorIcons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4 text-center"><?= $category ?> Stores</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php mysqli_data_seek($result, 0); // Reset result pointer ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white shadow-md rounded-lg p-4 relative">
                    <img src="<?= !empty($row['IMAGE']) && file_exists('../assets/store_img/' . $row['IMAGE']) ? '../assets/store_img/' . $row['IMAGE'] : '../assets/store_img/no_img.jpg' ?>"
                        class="w-full h-40 object-cover rounded-md">
                    <div class="text-lg font-semibold mt-2 flex w-full justify-between items-start">
                        <span>
                            <?= $row['SHOP_NAME'] ?>
                        </span>
                        <div class="absolute right-0 z-10">
                            <button class="text-gray-500 hover:text-gray-700 focus:outline-none dropdown-toggle px-4"
                                data-dropdown-id="dropdown-<?= $row['SHOP_ID'] ?>">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>

                            <!-- Dropdown Content -->
                            <div id="dropdown-<?= $row['SHOP_ID'] ?>"
                                class="hidden absolute right-0 mt-2 w-36 bg-white rounded-md shadow-lg z-20">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        onclick="openEditModal(<?= $row['SHOP_ID'] ?>, '<?= addslashes($row['SHOP_NAME']) ?>', '<?= addslashes($row['ADDRESS']) ?>', '<?= $row['AVAILABILITY'] ?>')">
                                        <i class="ph ph-pencil-simple mr-2"></i> Edit
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                        onclick="openDeleteModal(<?= $row['SHOP_ID'] ?>, '<?= addslashes($row['SHOP_NAME']) ?>')">
                                        <i class="ph ph-trash mr-2"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600"><?= $row['ADDRESS'] ?></p>
                    <p class="text-sm <?= $row['AVAILABILITY'] == 'Open' ? 'text-green-500' : 'text-red-500' ?>">
                        <?= $row['AVAILABILITY'] == 'Open' ? 'Open Now' : 'Closed' ?>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Edit Store</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="editForm" method="post" action="">
                <input type="hidden" name="shop_id" id="edit_store_id">
                <input type="hidden" name="edit_store" value="1">

                <div class="mb-4">
                    <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
                    <input type="text" id="shop_name" name="shop_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" id="address" name="address"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="availability" class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                    <select id="availability" name="availability"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="Open">Open</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Delete Store</h3>
                <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <p class="mb-4">Are you sure you want to delete <span id="deleteStoreName" class="font-semibold"></span>?
                This action cannot be undone.</p>

            <p class="mb-4 text-red-600 text-sm">
                <strong>Warning:</strong> This will also delete all related products, owner accounts, and user accounts
                associated with this store.
            </p>

            <form id="deleteForm" method="post" action="">
                <input type="hidden" name="shop_id" id="delete_store_id">
                <input type="hidden" name="delete_store" value="1">

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Improved dropdown toggling
        document.addEventListener('DOMContentLoaded', function () {
            // Add click handlers to all dropdown toggle buttons
            const dropdownToggleButtons = document.querySelectorAll('.dropdown-toggle');

            dropdownToggleButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.stopPropagation(); // Prevent event from bubbling up

                    const dropdownId = this.getAttribute('data-dropdown-id');
                    const dropdown = document.getElementById(dropdownId);

                    // Close all other dropdowns first
                    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                        if (el.id !== dropdownId) {
                            el.classList.add('hidden');
                        }
                    });

                    // Toggle the clicked dropdown
                    dropdown.classList.toggle('hidden');
                });
            });

            // Close all dropdowns when clicking outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.dropdown-toggle')) {
                    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                        el.classList.add('hidden');
                    });
                }
            });
        });

        // Edit Modal Functions
        function openEditModal(shopId, shopName, address, availability) {
            document.getElementById('edit_store_id').value = shopId;
            document.getElementById('shop_name').value = shopName;
            document.getElementById('address').value = address;
            document.getElementById('availability').value = availability;

            const modal = document.getElementById('editModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }

        // Delete Modal Functions
        function openDeleteModal(shopId, shopName) {
            document.getElementById('delete_store_id').value = shopId;
            document.getElementById('deleteStoreName').textContent = shopName;

            const modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }
    </script>
</body>

</html>