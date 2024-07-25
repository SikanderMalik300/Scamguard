<?php
session_start();

function checkSession() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../login.php');
        exit();
    }
}

function html_escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function loginUser($user_id) {
    $_SESSION['user_id'] = $user_id;
}

function logoutUser() {
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit();
}
?>
