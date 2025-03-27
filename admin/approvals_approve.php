<?php
include '../includes/connection.php';
require_once '../telerivet-php-client/telerivet.php';

$api_key = "iorNG_tQnRg6wi4gySKTK7ZHeqESikOr5JgM";
$project_id = "PJ59c301d36eac6193";

if (!isset($db)) {
    die("Database connection error.");
}

if (isset($_POST['owner_id'])) {
    $owner_id = intval($_POST['owner_id']);

    $query = "SELECT * FROM owners WHERE OWNER_ID = $owner_id";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $owner = mysqli_fetch_assoc($result);
        $owner_fname = trim($owner['FIRST_NAME']);
        $owner_lname = trim($owner['LAST_NAME']);
        $phone_number = $owner['PHONE_NUMBER'];

        $update_query = "UPDATE owners SET STATUS = 'Active' WHERE OWNER_ID = $owner_id";

        if (mysqli_query($db, $update_query)) {
            try {
                $telerivet = new Telerivet_API($api_key);
                $project = $telerivet->initProjectById($project_id);
                $message = "Hello " . strtoupper($owner_fname) . " " . strtoupper($owner_lname) . ", your store registration has been approved! You can now log in and manage your store.";

                $sent_msg = $project->sendMessage(array(
                    'to_number' => $phone_number,
                    'content' => $message
                ));

                echo "Message Sent! ID: " . $sent_msg->id . "<br>";
            } catch (Exception $e) {
                error_log("SMS failed: " . $e->getMessage());
                echo "Error sending SMS: " . $e->getMessage();
            }

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