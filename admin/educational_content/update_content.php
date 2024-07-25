<?php
include '../../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['id'];
  $title = $_POST['title'];
  $content = $_POST['content'];
  $video_url = $_POST['video_url'];

  // Update content logic (prepare statement, bind params, execute)
  $sql_update = "UPDATE educational_content SET title = ?, content = ?, video_url = ? WHERE id = ?";
  $stmt = $conn->prepare($sql_update);
  if ($stmt === false) {
    die('Prepare error: ' . $conn->error);
  }
  $stmt->bind_param('sssi', $title, $content, $video_url, $id);
  if (!$stmt->execute()) {
    die('Execute error: ' . $stmt->error);
  }
  $stmt->close();

  // Redirect back to manage_educational_content.php
  header("Location: manage_educational_content.php");
  exit();
}
?>