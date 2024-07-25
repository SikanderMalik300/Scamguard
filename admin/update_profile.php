<?php
include '../db_connect.php';
include '../session.php';
checkSession();

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email']; // This will be read-only
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $password = $_POST['password'];
    
    // Handle password update
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_BCRYPT); // Encrypt the new password
        $sql = "UPDATE users SET name = '$name', mobile = '$mobile', address = '$address', country = '$country', password = '$password' WHERE id = '$user_id'";
    } else {
        $sql = "UPDATE users SET name = '$name', mobile = '$mobile', address = '$address', country = '$country' WHERE id = '$user_id'";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the profile page with the success message
    header("Location: profile.php?message=" . urlencode($message));
    exit();
}
?>
