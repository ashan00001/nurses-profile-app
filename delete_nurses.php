<?php
session_start();

// Redirect if matron is not logged in
if (!isset($_SESSION['matron'])) {
    header("Location: login.html");
    exit();
}

include 'includes/db.php';

// Check if delete_ids is set and is an array
if (!empty($_POST['delete_ids']) && is_array($_POST['delete_ids'])) {
    foreach ($_POST['delete_ids'] as $employee_number) {
        // Sanitize and escape input
        $employee_number = $conn->real_escape_string(trim($employee_number));

        // Delete the nurse by employee number
        $conn->query("DELETE FROM nurses WHERE employee_number = '$employee_number'");
    }

    // Optional: You could log deletion or show success message using sessions
}

// Redirect back to the dashboard
header("Location: dashboard.php");
exit();
?>
