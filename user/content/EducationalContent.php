<?php
class EducationalContent {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetchContent() {
        $sql = "SELECT * FROM educational_content";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
