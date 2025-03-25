<?php
// Include database connection
include '../includes/connection.php';

// Include Twilio SDK
require_once '../vendor/autoload.php';
use Twilio\Rest\Client;

// Twilio credentials
$sid = "AC56fb29f2efe3428311cdaca293d2ed9a";
$token = "94b396a068defd8c4f84dcd9d64c753a";
$twilio_number = "+13346860947";

// Ensure DB connection exists
if (!isset($db)) {
    die("Database connection error.");
}

// Check if owner_id is passed
if (isset($_POST['owner_id'])) {
    $owner_id = intval($_POST['owner_id']); // Prevent SQL injection

    // Fetch owner's first name
    $query = "SELECT FIRST_NAME FROM owners WHERE OWNER_ID = $owner_id";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $owner = mysqli_fetch_assoc($result);
        $owner_name = trim($owner['FIRST_NAME']);

        // ✅ Fixed phone number (for testing)
        $phone_number = "+639815128418";

        // Update the status to 'Active'
        $update_query = "UPDATE owners SET STATUS = 'Active' WHERE OWNER_ID = $owner_id";

        if (mysqli_query($db, $update_query)) {
            // Send SMS Notification
            try {
                $client = new Client($sid, $token);
                $message = "Hello $owner_name, your store registration has been approved! You can now log in and manage your store.";

                $response = $client->messages->create(
                    $phone_number, // Fixed phone number
                    [
                        "from" => $twilio_number,
                        "body" => $message
                    ]
                );

                // Debugging: Print message SID and status
                echo "Message Sent! SID: " . $response->sid . "<br>";
                echo "Status: " . $response->status . "<br>";

            } catch (Exception $e) {
                error_log("SMS failed: " . $e->getMessage());
            }

            // Redirect to approvals page with success message
            header("Location: approvals.php?status=approved");
            exit();
        } else {
            die("Error updating status: " . mysqli_error($db));
        }
    } else {
        die("Owner not found.");
    }
} else {
    die("Owner ID is missing.");
}
?>