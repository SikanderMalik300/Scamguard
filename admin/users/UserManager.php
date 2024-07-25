<?php

class UserManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUsers() {
        $sql = "SELECT id, name, email, mobile, address, country FROM users WHERE type = 'user'";
        $result = $this->conn->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception('Error fetching users: ' . $this->conn->error);
        }
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email, mobile, address, country FROM users WHERE id = ? AND type = 'user'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            throw new Exception('User not found.');
        }
    }
}
