<?php
include '../db_connect.php';
include_once '../session.php';
include 'User.php'; // Include the User class

checkSession();

$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email']; // This will be read-only
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $password = $_POST['password'];

    if ($user->updateProfile($name, $email, $mobile, $address, $country, $password)) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error: Unable to update profile.";
    }

    // Redirect back to the profile page with the success message
    header("Location: profile.php?message=" . urlencode($message));
    exit();
}
?>
