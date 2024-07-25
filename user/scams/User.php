<?php
class User {
    private $conn;
    private $user_id;

    public function __construct($conn, $user_id) {
        $this->conn = $conn;
        $this->user_id = $user_id;
    }

    public function getName() {
        $stmt = $this->conn->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->bind_param('i', $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user['name'] ?? null;
    }
}
?>
