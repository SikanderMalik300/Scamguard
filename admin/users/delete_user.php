<?php
// delete_user.php

require_once '../../db_connect.php'; // Include your database connection file
include '../../session.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure the ID is an integer

    // Prepare a statement to delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    try {
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Redirect to manage_users.php with a success message
            header("Location: manage_users.php?status=deleted");
        } else {
            // Redirect to manage_users.php with an error message
            header("Location: manage_users.php?status=notfound");
        }
    } catch (mysqli_sql_exception $e) {
        // Check for foreign key constraint violation
        if ($e->getCode() == 1451) { // Error code for foreign key constraint fails
            header("Location: manage_users.php?status=foreignkey");
        } else {
            header("Location: manage_users.php?status=error");
        }
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    // Redirect to manage_users.php if 'id' is not provided
    header("Location: manage_users.php?status=missingid");
}
exit();
?>
