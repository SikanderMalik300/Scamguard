<?php
include '../../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']); // Ensure the ID is an integer

        $sql_delete = "DELETE FROM educational_content WHERE id = ?";
        $stmt = $conn->prepare($sql_delete);

        if ($stmt) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: manage_educational_content.php");
                exit();
            } else {
                // Error during execution
                echo "Error: Could not execute the query.";
            }
        } else {
            // Error preparing statement
            echo "Error: Could not prepare the statement.";
        }
    } else {
        // Invalid ID
        echo "Error: Invalid ID.";
    }
} else {
    // Invalid request method
    echo "Error: Invalid request method.";
}
?>
