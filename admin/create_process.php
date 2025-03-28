<?php
require('../includes/connection.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $first_name = trim($_POST['first_name']);
    $middle_name = isset($_POST['middle_name']) ? trim($_POST['middle_name']) : ''; // Optional
    $last_name = trim($_POST['last_name']);
    $suffix = isset($_POST['suffix']) ? trim($_POST['suffix']) : ''; // Optional
    $username = trim($_POST['username']);
    $password = $_POST['password']; // Get the raw password
    $store_id = $_POST['store_id'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $street = null; // Street is set to null
    $purok = $_POST['purok'];
    $barangay = $_POST['barangay'];
    $shop_name = trim($_POST['shop_name']);
    $hired_date = date('Y-m-d');
    $status = 'Pending';

    // Set default values for JOB_ID and TYPE_ID
    $type_id = 3; // Set TYPE_ID to 3 (Manager)
    $job_id = 0; // Set job_id explicitly for the owner

    // Generate SHOP_ID (move this up to be before document file handling)
    $shop_id = mt_rand(10000000, 99999999); // Generate a random 8-digit SHOP_ID

    // Initialize document variables as null
    $document_1 = null;
    $document_2 = null;

    // Handle file uploads for DOCUMENT_1 and DOCUMENT_2
    if (isset($_FILES['document_1']) && $_FILES['document_1']['error'] == UPLOAD_ERR_OK) {
        $document_1_filename = '../assets/documents/' . $shop_id . '1' . '.' . pathinfo($_FILES['document_1']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['document_1']['tmp_name'], $document_1_filename);
        $document_1 = $document_1_filename;
    } else {
        $document_1 = null;
    }

    if (isset($_FILES['document_2']) && $_FILES['document_2']['error'] == UPLOAD_ERR_OK) {
        $document_2_filename = '../assets/documents/' . $shop_id . '2' . '.' . pathinfo($_FILES['document_2']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['document_2']['tmp_name'], $document_2_filename);
        $document_2 = $document_2_filename;
    } else {
        $document_2 = null;
    }

    // Validation: check required fields (middle_name and suffix are optional)
    if (empty($first_name) || empty($last_name) || empty($username) || empty($password) || empty($store_id) || empty($gender) || empty($email) || empty($phone_number) || empty($purok) || empty($barangay) || empty($shop_name)) {
        die('All fields are required.');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format.');
    }

    // Check if username already exists in the database
    $stmt = $db->prepare("SELECT 1 FROM users WHERE USERNAME = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die('Username already exists.');
    }

    // Check if email already exists in the owners table
    $stmt_email = $db->prepare("SELECT 1 FROM owners WHERE EMAIL = ?");
    $stmt_email->bind_param('s', $email);
    $stmt_email->execute();
    $stmt_email->store_result();

    if ($stmt_email->num_rows > 0) {
        die('Email already exists.');
    }

    // Insert the location into the location table
    $insert_location_stmt = $db->prepare("INSERT INTO location (STREET, PUROK, BARANGAY) VALUES (?, ?, ?)");
    $insert_location_stmt->bind_param('sss', $street, $purok, $barangay);

    if (!$insert_location_stmt->execute()) {
        die('Failed to insert location.');
    }

    // Get the generated LOCATION_ID
    $location_id = $db->insert_id;

    // Generate a random 5-digit OWNER_ID
    $owner_id = mt_rand(10000, 99999);

    // Insert into owners table with the additional middle name and suffix fields
    // Corrected type string: 10th parameter (hired_date) should be "s" not "i"
    $insert_owner_stmt = $db->prepare("INSERT INTO owners (OWNER_ID, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX, JOB_ID, GENDER, EMAIL, PHONE_NUMBER, HIRED_DATE, LOCATION_ID, STATUS, SHOP_ID, DOCUMENT_1, DOCUMENT_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_owner_stmt->bind_param(
        'issssissssisiss', // Note the change at the 10th position: hired_date is now "s"
        $owner_id,
        $first_name,
        $middle_name,
        $last_name,
        $suffix,
        $job_id,
        $gender,
        $email,
        $phone_number,
        $hired_date,
        $location_id,
        $status,
        $shop_id,
        $document_1,
        $document_2
    );

    if (!$insert_owner_stmt->execute()) {
        die('Failed to add owner.');
    }

    // Insert into stores table with SHOP_ID, SHOP_NAME, and STORE_ID
    $insert_store_stmt = $db->prepare("INSERT INTO stores (SHOP_ID, SHOP_NAME, STORE_ID) VALUES (?, ?, ?)");
    $insert_store_stmt->bind_param('isi', $shop_id, $shop_name, $store_id);

    if (!$insert_store_stmt->execute()) {
        die('Failed to insert store information.');
    }

    // Insert into users table with the plain-text password, active status, and TYPE_ID = 3, with OWNER_ID
    $insert_user_stmt = $db->prepare("INSERT INTO users (OWNER_ID, USERNAME, PASSWORD, TYPE_ID, STORE_ID, SHOP_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_user_stmt->bind_param('isssis', $owner_id, $username, $password, $type_id, $store_id, $shop_id);

    if ($insert_user_stmt->execute()) {
        session_start();
        $_SESSION['success_message'] = 'Account successfully created! Your shop ID is ' . $shop_id . '.';

        // Redirect to receipt.php with the shop_id
        header('Location: receipt.php?shop_id=' . $shop_id);
        exit;
    } else {
        die('Failed to create account.');
    }
}
?>