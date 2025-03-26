<?php
session_start();
include '../../includes/connection.php';

$owner_id = $_SESSION['OWNER_ID'];

// Fetch account details
$query_user = "SELECT USERNAME, PASSWORD FROM users WHERE OWNER_ID = ?";
$stmt_user = mysqli_prepare($db, $query_user);
mysqli_stmt_bind_param($stmt_user, "i", $owner_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user = mysqli_fetch_assoc($result_user);

// If no account found, set default values
if (!$user) {
    $user = ["USERNAME" => "", "PASSWORD" => ""];
}


// Fetch owner details
$query_owner = "SELECT * FROM owners WHERE OWNER_ID = ?";
$stmt_owner = mysqli_prepare($db, $query_owner);
mysqli_stmt_bind_param($stmt_owner, "i", $owner_id);
mysqli_stmt_execute($stmt_owner);
$result_owner = mysqli_stmt_get_result($stmt_owner);
$owner = mysqli_fetch_assoc($result_owner);

// Fetch store details
$query_store = "SELECT * FROM stores WHERE SHOP_ID = (SELECT SHOP_ID FROM owners WHERE OWNER_ID = ?)";
$stmt_store = mysqli_prepare($db, $query_store);
mysqli_stmt_bind_param($stmt_store, "i", $owner_id);
mysqli_stmt_execute($stmt_store);
$result_store = mysqli_stmt_get_result($stmt_store);
$store = mysqli_fetch_assoc($result_store);

// Ensure LOCATION_ID is set before querying location table
$location_id = $owner['LOCATION_ID'] ?? null;
$full_address = "Not Set";

if ($location_id) {
    $query_location = "SELECT PUROK, BARANGAY FROM location WHERE LOCATION_ID = ?";
    $stmt_location = mysqli_prepare($db, $query_location);
    mysqli_stmt_bind_param($stmt_location, "i", $location_id);
    mysqli_stmt_execute($stmt_location);
    $result_location = mysqli_stmt_get_result($stmt_location);
    $location = mysqli_fetch_assoc($result_location);
    if ($location) {
        $full_address = "{$location['PUROK']}, {$location['BARANGAY']}, Daraga, Albay";
    }
}

// Fetch account details
$query_user = "SELECT * FROM users WHERE OWNER_ID = ?";
$stmt_user = mysqli_prepare($db, $query_user);
mysqli_stmt_bind_param($stmt_user, "i", $owner_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user = mysqli_fetch_assoc($result_user);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_owner'])) {
        $first_name = $_POST['FIRST_NAME'];
        $last_name = $_POST['LAST_NAME'];
        $gender = $_POST['GENDER'];
        $email = $_POST['EMAIL'];
        $phone = $_POST['PHONE_NUMBER'];

        $update_owner = "UPDATE owners SET FIRST_NAME=?, LAST_NAME=?, GENDER=?, EMAIL=?, PHONE_NUMBER=? WHERE OWNER_ID=?";
        $stmt_update = mysqli_prepare($db, $update_owner);
        mysqli_stmt_bind_param($stmt_update, "sssssi", $first_name, $last_name, $gender, $email, $phone, $owner_id);
        mysqli_stmt_execute($stmt_update);
    }

    if (isset($_POST['update_store'])) {
        $shop_name = $_POST['SHOP_NAME'];

        // Handle File Upload
        if (!empty($_FILES['STORE_IMAGE']['name'])) {
            $target_dir = "../../assets/store_img/";
            $file_name = basename($_FILES["STORE_IMAGE"]["name"]);
            $target_file = $target_dir . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate Image File
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $allowed_types)) {
                if (move_uploaded_file($_FILES["STORE_IMAGE"]["tmp_name"], $target_file)) {
                    // Update store image in database
                    $update_image_query = mysqli_prepare($db, "UPDATE stores SET IMAGE = ? WHERE SHOP_ID = ?");
                    mysqli_stmt_bind_param($update_image_query, "si", $file_name, $store['SHOP_ID']);
                    mysqli_stmt_execute($update_image_query);
                }
            }
        }

        // Update other store details
        $update_store_query = mysqli_prepare($db, "UPDATE stores SET SHOP_NAME = ? WHERE SHOP_ID = ?");
        mysqli_stmt_bind_param($update_store_query, "si", $shop_name, $store['SHOP_ID']);
        mysqli_stmt_execute($update_store_query);

        // Redirect to refresh data
        header("Location: settings.php?tab=store");
        exit();
    }



    if (isset($_POST['update_account'])) {
        $username = $_POST['USERNAME'];
        $password = $_POST['PASSWORD']; // Get the new password

        // Update username
        $update_user = "UPDATE users SET USERNAME=? WHERE OWNER_ID=?";
        $stmt_update = mysqli_prepare($db, $update_user);
        mysqli_stmt_bind_param($stmt_update, "si", $username, $owner_id);
        mysqli_stmt_execute($stmt_update);

        // Update password only if a new one is provided
        if (!empty($password)) {
            $update_password = "UPDATE users SET PASSWORD=? WHERE OWNER_ID=?";
            $stmt_pass = mysqli_prepare($db, $update_password);
            mysqli_stmt_bind_param($stmt_pass, "si", $password, $owner_id);
            mysqli_stmt_execute($stmt_pass);
        }

        header("Location: settings.php?tab=account");
        exit();
    }



    header("Location: settings.php?tab=" . $_POST['tab']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
        body {
            background-image: url('../../img/hardware_bg.jpg');
            /* Change to a relevant image */
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        .metal-box {
            background: linear-gradient(145deg, #b8b8b8, #e0e0e0);
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.3);
            border: 1px solid #aaa;
            border-radius: 8px;
            padding: 20px;
        }

        .metal-btn {
            background: linear-gradient(145deg, #6d6d6d, #9c9c9c);
            color: white;
            border: 1px solid #555;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .metal-btn:hover {
            background: linear-gradient(145deg, #5a5a5a, #7d7d7d);
        }

        .tab-active {
            border-bottom: 4px solid #ff0000;
            font-weight: bold;
            color: #ff0000;
        }
    </style>
</head>

<body>
    <?php include '../include/topbar.php'; ?>

    <div class="max-w-4xl mx-auto mt-10 p-6 metal-box relative">
        <div class="flex justify-between items-center border-b pb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Settings</h2>
            <button type="button" class="metal-btn edit-btn">
                <i class="fas fa-pencil-alt"></i>
            </button>
        </div>

        <div class="flex border-b mb-6">
            <?php
            $tabs = [
                "owner" => ["Owner Details", "fa-user"],
                "store" => ["Store Details", "fa-store"],
                "account" => ["Account Details", "fa-key"],
                "delete" => ["Delete Account", "fa-trash"]
            ];
            $current_tab = $_GET['tab'] ?? 'owner';
            foreach ($tabs as $key => [$label, $icon]) {
                $active = $current_tab === $key ? "tab-active" : "text-gray-600";
                echo "<a href='?tab=$key' class='flex items-center space-x-2 px-4 py-2 hover:text-red-500 $active'>
                    <i class='fas $icon'></i> <span>$label</span>
                  </a>";
            }
            ?>
        </div>

        <?php if ($current_tab === 'owner'): ?>
            <div>
                <form method="POST" class="mt-4 space-y-3">
                    <input type="hidden" name="tab" value="owner">
                    <?php foreach (["FIRST_NAME", "LAST_NAME", "GENDER", "EMAIL", "PHONE_NUMBER"] as $field): ?>
                        <div>
                            <label class="block text-gray-600"><?= str_replace("_", " ", $field) ?>:</label>
                            <input type="text" name="<?= $field ?>" value="<?= $owner[$field] ?>"
                                class="border p-2 w-full rounded edit-field" disabled>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach (["DOCUMENT_1", "DOCUMENT_2"] as $field): ?>
                        <div>
                            <label class="block text-gray-600"><?= str_replace("_", " ", $field) ?>:</label>
                            <a href="<?= $owner[$field] ?>" target="_blank"
                                class="text-blue-500"><?= basename($owner[$field]) ?></a>
                        </div>
                    <?php endforeach; ?>

                    <button type="submit" name="update_owner" class="metal-btn save-btn hidden">Save</button>
                    <button type="button" class="metal-btn cancel-btn hidden">Cancel</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($current_tab === 'store'): ?>
            <div>
                <form method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                    <input type="hidden" name="tab" value="store">

                    <!-- Store Image -->
                    <div class="mb-3">
                        <label class="block text-gray-600">Store Image:</label>
                        <div class="relative w-40 h-40 border rounded overflow-hidden">
                            <?php
                            $image_path = "../../assets/store_img/" . $store['IMAGE'];
                            $default_image = "../../assets/store_img/no_img.jpg";
                            $display_image = (!empty($store['IMAGE']) && file_exists($image_path)) ? $image_path : $default_image;
                            ?>
                            <img id="storeImagePreview" src="<?= $display_image; ?>" alt="Store Image"
                                class="w-full h-full object-cover">
                            <input type="file" name="STORE_IMAGE" accept="image/*"
                                class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-600">Shop Name:</label>
                        <input type="text" name="SHOP_NAME" value="<?= $store['SHOP_NAME'] ?>"
                            class="border p-2 w-full rounded edit-field" disabled>
                    </div>

                    <?php
                    $location_query = mysqli_prepare($db, "SELECT PUROK, BARANGAY FROM location WHERE LOCATION_ID = ?");
                    mysqli_stmt_bind_param($location_query, "i", $owner['LOCATION_ID']);
                    mysqli_stmt_execute($location_query);
                    $result_location = mysqli_stmt_get_result($location_query);
                    $location = mysqli_fetch_assoc($result_location);
                    ?>

                    <div>
                        <label class="block text-gray-600">Purok:</label>
                        <input type="text" name="PUROK" value="<?= $location['PUROK'] ?>"
                            class="border p-2 w-full rounded edit-field" disabled>
                    </div>

                    <div>
                        <label class="block text-gray-600">Barangay:</label>
                        <input type="text" name="BARANGAY" value="<?= $location['BARANGAY'] ?>"
                            class="border p-2 w-full rounded edit-field" disabled>
                    </div>

                    <div>
                        <label class="block text-gray-600">City:</label>
                        <input type="text" value="Daraga, Albay" class="border p-2 w-full rounded bg-gray-200" disabled>
                    </div>

                    <button type="submit" name="update_store" class="metal-btn save-btn hidden">Save</button>
                    <button type="button" class="metal-btn cancel-btn hidden">Cancel</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($current_tab === 'account'): ?>
            <div>
                <form method="POST" class="mt-4 space-y-3">
                    <input type="hidden" name="tab" value="account">

                    <div>
                        <label class="block text-gray-600">Username:</label>
                        <input type="text" name="USERNAME" value="<?= htmlspecialchars($user['USERNAME']) ?>"
                            class="border p-2 w-full rounded edit-field" disabled>
                    </div>

                    <div>
                        <label class="block text-gray-600">Password:</label>
                        <input type="password" name="PASSWORD" placeholder="********"
                            class="border p-2 w-full rounded edit-field" disabled>
                        <small class="text-gray-500">Leave empty to keep current password</small>
                    </div>

                    <button type="submit" name="update_account" class="metal-btn save-btn hidden">Save</button>
                    <button type="button" class="metal-btn cancel-btn hidden">Cancel</button>
                </form>
            </div>
        <?php endif; ?>

    </div>

    <script src="script.js"></script>
</body>

</html>


<script>
    document.querySelector('.edit-btn').addEventListener('click', function () {
        document.querySelectorAll('.edit-field').forEach(input => input.removeAttribute('disabled'));
        document.querySelectorAll('.save-btn, .cancel-btn').forEach(btn => btn.classList.remove('hidden'));
        this.classList.add('hidden');
    });

    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.edit-field').forEach(input => input.setAttribute('disabled', 'true'));
            document.querySelector('.edit-btn').classList.remove('hidden');
            document.querySelectorAll('.save-btn, .cancel-btn').forEach(btn => btn.classList.add('hidden'));
        });
    });

</script>