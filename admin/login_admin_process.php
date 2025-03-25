<?php
session_start();
require('../includes/connection.php');

$username = trim($_POST['user']);
$password = trim($_POST['password']);

if (empty($password)) {
    header("Location: login_admin.php?missingpassword=true");
    exit();
}

// Query to check if the user exists and fetch necessary details, including store type
$sql = "SELECT u.ID, u.SHOP_ID, u.STORE_ID, u.OWNER_ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, 
        e.EMAIL, e.PHONE_NUMBER, l.PUROK, l.BARANGAY, t.TYPE, u.PASSWORD, e.STATUS, 
        st.CATEGORY  -- Fetch store type category
        FROM `users` u
        JOIN `owners` e ON e.OWNER_ID = u.OWNER_ID
        JOIN `location` l ON e.LOCATION_ID = l.LOCATION_ID
        JOIN `type` t ON t.TYPE_ID = u.TYPE_ID
        LEFT JOIN `store_type` st ON st.STORE_ID = u.STORE_ID -- Get store category
        WHERE `USERNAME` = ?";

if ($stmt = $db->prepare($sql)) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_password = trim($user['PASSWORD']);

        // Check if user is under review
        if ($user['STATUS'] === 'Pending') {
            header("Location: login-admin.php?pending=true");
            exit();
        }

        // Check if password is correct
        if ($password === $stored_password) {
            setupUserSession($user);

            // Redirect based on user type and store category
            if ($user['TYPE'] === 'Admin') {
                header("Location: index.php");
                exit();
            } elseif ($user['TYPE'] === 'Manager') {
                switch ($user['CATEGORY']) {
                    case 'Meatshop':
                        header("Location: ../meatshop/");
                        break;
                    case 'Animal Feeds':
                        header("Location: ../animalfeed/");
                        break;
                    case 'Hardware':
                        header("Location: ../hardware/");
                        break;
                    default:
                        header("Location: ../user/");
                        break;
                }
                exit();
            }
        } else {
            header("Location: login-admin.php?wrongcredentials=true");
            exit();
        }
    } else {
        header("Location: login-admin.php?usernotexist=true");
        exit();
    }
    $stmt->close();
} else {
    echo "Error: " . $db->error;
}

// Function to store user session
function setupUserSession($user)
{
    $_SESSION['USER_ID'] = $user['ID'];
    $_SESSION['FIRST_NAME'] = $user['FIRST_NAME'];
    $_SESSION['LAST_NAME'] = $user['LAST_NAME'];
    $_SESSION['GENDER'] = $user['GENDER'];
    $_SESSION['EMAIL'] = $user['EMAIL'];
    $_SESSION['PHONE_NUMBER'] = $user['PHONE_NUMBER'];
    $_SESSION['TYPE'] = $user['TYPE'];
    $_SESSION['OWNER_ID'] = $user['OWNER_ID'];
    $_SESSION['SHOP_ID'] = $user['SHOP_ID'];
    $_SESSION['STORE_ID'] = $user['STORE_ID'];
    $_SESSION['CATEGORY'] = $user['CATEGORY']; // Store category in session
}
?>