<?php
include '../../db_connect.php';
include '../../session.php';
include 'User.php';
include 'Report.php';
checkSession();

$user_id = $_SESSION['user_id'];

$user = new User($conn, $user_id);
$user_details = $user->getUserDetails();

$sql_reports = "SELECT sr.id, sr.scam_title, sr.description, sr.status, sr.evidence, u.name as user_name 
                FROM scam_reports sr 
                JOIN users u ON sr.user_id = u.id";
$result_reports = $conn->query($sql_reports);
$reports = $result_reports->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Reports</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #1e90ff, #00bfff);
            color: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        header img {
            height: 50px;
        }

        .header-title {
            font-size: 28px;
            margin: 0;
            font-weight: 700;
        }

        .dropdown {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            min-width: 200px;
            z-index: 1000;
            border-radius: 8px;
            overflow: hidden;
            padding: 0;
        }

        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 16px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dropdown-content a:hover {
            background-color: #f0f0f0;
            color: #1e90ff;
        }

        .dropdown-content .icon {
            margin-right: 10px;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        h3 a {
    text-decoration: none;
    color: #1e90ff;
}

        .dropdown-toggle .icon {
            font-size: 18px;
            margin-left: 8px;
        }

        .main-container {
            display: flex;
            flex: 1;
            flex-direction: row;
            overflow: hidden;
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar {
            width: 220px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
            border-right: 1px solid #ddd;
        }

        .sidebar h3 {
            color: #1e90ff;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #333;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #e6e6e6;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #f0f8ff;
            overflow-y: auto;
            box-sizing: border-box;
        }

        .content h2 {
            color: #1e90ff;
            margin-top: 0;
            font-size: 24px;
        }

        .content .table-container {
            overflow-x: auto;
        }

        .content table {
            min-width: 100%;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-collapse: collapse;
            margin-top: 20px;
        }

        .content th, .content td {
            padding: 12px 15px;
            text-align: left;
        }

        .content th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }

        .content td {
            color: #666;
        }

        .content tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .content tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            background-color: #1e90ff;
            color: white;
            text-align: center;
            padding: 10px 20px;
            position: relative;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        footer img {
            height: 30px;
            vertical-align: middle;
        }

        .selected {
            background-color: #e6e6e6;
        }

        .copyright {
            margin-top: 10px;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .sidebar {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #ddd;
                box-shadow: none;
            }

            .main-container {
                flex-direction: column;
            }

            .content {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 24px;
            }

            header img {
                height: 40px;
            }

            .dropdown-content {
                min-width: 150px;
            }

            .dropdown-content a {
                font-size: 14px;
                padding: 10px 14px;
            }

            .sidebar ul li a {
                font-size: 14px;
                padding: 8px;
            }

            .content {
                padding: 10px;
            }
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .approve-button, .reject-button {
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .approve-button:hover {
            background-color: #007acc;
        }
        .reject-button {
            background-color: #ff4c4c;
        }
        .reject-button:hover {
            background-color: #cc0000;
        }

        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 30px;
            font-size: 17px;
        }

        .toast.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @-webkit-keyframes fadein {
            from {top: 0; opacity: 0;}
            to {top: 30px; opacity: 1;}
        }

        @keyframes fadein {
            from {top: 0; opacity: 0;}
            to {top: 30px; opacity: 1;}
        }

        @-webkit-keyframes fadeout {
            from {top: 30px; opacity: 1;}
            to {top: 0; opacity: 0;}
        }

        @keyframes fadeout {
            from {top: 30px; opacity: 1;}
            to {top: 0; opacity: 0;}
        }
    </style>
</head>
<body>
    <header>
        <h1 class="header-title">ScamGuard</h1>
        <img src="../../assets/logo.png" alt="ScamGuard Logo">
        <div class="dropdown">
            <span>Admin</span>
            <div class="dropdown-content">
                <a href="../profile.php"><i class="icon">👤</i>Profile</a>
                <a href="../logout.php"><i class="icon">🚪</i>Logout</a>
            </div>
        </div>
    </header>
    <div class="main-container">
        <div class="sidebar">
            <h3><a href="../admin_dashboard.php">Admin Dashboard</a></h3>
            <ul>
                <li class='selected'><a href="view_all_reports.php">View All Reports</a></li>
                <li><a href="../users/manage_users.php">Manage Users</a></li>
                <li><a href="../educational_content/add_educational_content.php">Add Educational Content</a></li>
                <li><a href="../educational_content/manage_educational_content.php">Manage Educational Content</a></li>
            </ul>
        </div>
        <div class="content">
            <h2>All Scam Reports</h2>
            <div class="table-container">
                <?php if (empty($reports)): ?>
                    <p class="text-center text-lg">No reports found.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Evidence</th>
                                <th>Submitted By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?php echo html_escape($report['id']); ?></td>
                                    <td><?php echo html_escape($report['scam_title']); ?></td>
                                    <td><?php echo html_escape($report['description']); ?></td>
                                    <td><?php echo html_escape($report['status']); ?></td>
                                    <td>
                                        <?php
                                        $evidence_files = explode(',', $report['evidence']);
                                        foreach ($evidence_files as $file_path):
                                            $file_name = basename($file_path);
                                        ?>
                                            <a href="../uploads/<?php echo html_escape($file_path); ?>" download><?php echo html_escape($file_name); ?></a><br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><?php echo html_escape($report['user_name']); ?></td>
                                    <td class="action-buttons">
                                        <?php if ($report['status'] == 'Pending'): ?>
                                            <form action="approve_report.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo html_escape($report['id']); ?>">
                                                <button type="submit" class="approve-button">Approve</button>
                                            </form>
                                            <form action="reject_report.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo html_escape($report['id']); ?>">
                                                <button type="submit" class="reject-button">Reject</button>
                                            </form>
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>