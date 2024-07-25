<?php
require_once '../../db_connect.php';
require_once 'UserManager.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $userManager = new UserManager($conn);
    try {
        $user = $userManager->getUserById($id);
        echo json_encode($user);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
