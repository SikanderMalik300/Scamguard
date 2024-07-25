<?php

class ScamReport
{
    private $conn;
    private $user;

    public function __construct($conn, $user)
    {
        $this->conn = $conn;
        $this->user = $user;
    }

    public function submitReport($scam_title, $description, $evidence_files)
    {
        $upload_dir = '../../uploads/';
        $uploaded_files = [];
        $error_message = '';

        foreach ($evidence_files['name'] as $key => $filename) {
            $file_tmp = $evidence_files['tmp_name'][$key];
            $file_path = $upload_dir . basename($filename);

            $allowed_types = [
                'image/jpeg', 'image/png', 'image/gif', 'application/pdf',
                'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            if (!in_array($evidence_files['type'][$key], $allowed_types) || $evidence_files['size'][$key] > 5242880) {
                return 'Invalid file type or size.';
            }

            if (move_uploaded_file($file_tmp, $file_path)) {
                $uploaded_files[] = $file_path;
            } else {
                return 'Failed to upload some files.';
            }
        }

        $evidence_list = implode(',', $uploaded_files);
        $stmt = $this->conn->prepare("INSERT INTO scam_reports (user_id, scam_title, description, evidence) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isss', $this->user['id'], $scam_title, $description, $evidence_list);

        if ($stmt->execute()) {
            return 'Report submitted successfully!';
        } else {
            return 'Failed to submit report. Please try again.';
        }
    }
}
?>
