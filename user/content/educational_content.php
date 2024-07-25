<?php
include '../../db_connect.php';
include '../../session.php';
include 'EducationalContent.php';
include 'User.php';
include 'Template.php';

// Check session
checkSession();

// Initialize objects
$userId = $_SESSION['user_id'];
$user = new User($conn, $userId);
$educationalContent = new EducationalContent($conn);
$userDetails = $user->fetchUserDetails();
$contents = $educationalContent->fetchContent();

// Render template
$template = new Template($userDetails, $contents);
$template->render();
?>
