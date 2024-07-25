<?php
include '../../db_connect.php';
include '../../session.php';
checkSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_id = $_POST['id'];

    // Update the status of the report
    $stmt = $conn->prepare("UPDATE scam_reports SET status = 'Rejected' WHERE id = ?");
    $stmt->bind_param("i", $report_id);
    
    if ($stmt->execute()) {
        // Redirect back to the reports page with a success message
        header("Location: view_all_reports.php?status=rejected");
    } else {
        // Redirect back to the reports page with an error message
        header("Location: view_all_reports.php?status=error");
    }
    
    $stmt->close();
}
?>
