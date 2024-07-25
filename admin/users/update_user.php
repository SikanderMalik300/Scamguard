<?php
require_once '../../db_connect.php'; // Include your database configuration file
include '../../session.php';
checkSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : null;
    $address = isset($_POST['address']) ? $_POST['address'] : null;
    $country = isset($_POST['country']) ? $_POST['country'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if ($id && $name && $email && $mobile && $address && $country) {
        // Prepare update query
        $sql = "UPDATE users SET name = ?, email = ?, mobile = ?, address = ?, country = ?";

        if ($password) {
            // Update password if provided
            $sql .= ", password = ? WHERE id = ?";
        } else {
            $sql .= " WHERE id = ?";
        }

        $stmt = $conn->prepare($sql);

        if ($password) {
            $stmt->bind_param("ssssssi", $name, $email, $mobile, $address, $country, $password, $id);
        } else {
            $stmt->bind_param("sssssi", $name, $email, $mobile, $address, $country, $id);
        }

        if ($stmt->execute()) {
            // Redirect to manage users with a success message
            header('Location: manage_users.php');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in all required fields.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
