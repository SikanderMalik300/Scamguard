<?php
include '../db_connect.php';
include_once '../session.php';


class User {
    private $conn;
    private $user_id;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->user_id = $_SESSION['user_id'];
    }

    public function getProfile() {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProfile($name, $email, $mobile, $address, $country, $password) {
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_BCRYPT); // Encrypt the new password
            $sql = "UPDATE users SET name = ?, mobile = ?, address = ?, country = ?, password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $mobile, $address, $country, $password, $this->user_id);
        } else {
            $sql = "UPDATE users SET name = ?, mobile = ?, address = ?, country = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssi", $name, $mobile, $address, $country, $this->user_id);
        }
        return $stmt->execute();
    }
}
?>
