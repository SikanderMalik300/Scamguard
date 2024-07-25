<?php
include '../../db_connect.php';
include '../../session.php';
include 'User.php';
include 'Report.php';
checkSession();

$user_id = $_SESSION['user_id'];

$user = new User($conn, $user_id);
$user_details = $user->getUserDetails();

$report = new Report($conn, $user_id);
$reports = $report->getUserReports();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f8ff; /* Light blue background */
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

header {
    background: linear-gradient(135deg, #1e90ff, #00bfff); /* Gradient background */
    color: white;
    padding: 15px 25px; /* Increased padding for a more spacious look */
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Softer shadow for depth */
    position: relative; /* Ensure dropdowns align correctly */
}

header img {
    height: 50px; /* Slightly larger logo */
}

.header-title {
    font-size: 28px; /* Larger font size for a prominent title */
    margin: 0;
    font-weight: 700; /* Bold font for emphasis */
}

.dropdown {
    position: relative;
    display: inline-flex; /* Adjust to fit the content */
    align-items: center;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%; /* Position right below the dropdown trigger */
    right: 0;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Softer shadow for depth */
    min-width: 200px; /* Adjust based on content */
    z-index: 1000;
    border-radius: 8px; /* Rounded corners for a modern look */
    overflow: hidden; /* Ensure no content spills over */
    padding: 0;
}

.dropdown-content a {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: flex;
    align-items: center;
    font-size: 16px;
    transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transitions */
}

.dropdown-content a:hover {
    background-color: #f0f0f0;
    color: #1e90ff; /* Highlight color on hover */
}

.dropdown-content .icon {
    margin-right: 10px; /* Space between icon and text */
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown-toggle {
    display: flex;
    align-items: center;
    cursor: pointer;
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
    padding: 20px; /* Added padding for spacing */
    box-sizing: border-box; /* Ensure padding is included in element's width/height */
}

.sidebar {
    width: 220px; /* Slightly wider sidebar */
    background-color: #ffffff;
    padding: 20px;
    box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
    border-right: 1px solid #ddd;
    transition: width 0.3s ease; /* Smooth transition for responsive adjustments */
}

.sidebar h3 {
    color: #1e90ff;
    font-size: 20px; /* Larger font size for sidebar headings */
    margin-top: 0;
    margin-bottom: 20px;
    font-weight: 600; /* Bold font for emphasis */
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
    border-radius: 4px; /* Rounded corners for links */
    transition: background-color 0.3s ease; /* Smooth transition */
}

.sidebar ul li a:hover {
    background-color: #e6e6e6; /* Subtle hover effect */
}

.content {
    flex: 1;
    padding: 20px;
    background-color: #f0f8ff;
    overflow-y: auto;
    box-sizing: border-box; /* Ensure padding is included in element's width/height */
}

.content h2 {
    color: #1e90ff;
    margin-top: 0;
    font-size: 24px; /* Larger font size for headings */
}
        .content .table-container {
            overflow-x: auto;
        }

        h3 a {
    text-decoration: none;
    color: #1e90ff;
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
        width: 200px; /* Slightly narrower sidebar for medium screens */
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #ddd;
        box-shadow: none; /* Remove shadow for smaller screens */
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
        font-size: 24px; /* Adjust header title size for smaller screens */
    }

    header img {
        height: 40px; /* Adjust logo size for smaller screens */
    }

    .dropdown-content {
        min-width: 150px; /* Adjust dropdown width for smaller screens */
    }

    .dropdown-content a {
        font-size: 14px; /* Adjust font size for dropdown items */
        padding: 10px 14px; /* Adjust padding for dropdown items */
    }

    .sidebar ul li a {
        font-size: 14px; /* Adjust font size for sidebar links */
        padding: 8px; /* Adjust padding for sidebar links */
    }

    .content {
        padding: 10px;
    }
}

    </style>
</head>
<body>
    <header>
        <h1 class="header-title">ScamGuard</h1>
        <img src="../../assets/logo.png" alt="ScamGuard Logo">
        <div class="dropdown">
            <span><?php echo html_escape($user_details['name']); ?></span>
            <div class="dropdown-content">
                <a href="../profile.php"><i class="icon">👤</i>Profile</a>
                <a href="../logout.php"><i class="icon">🚪</i>Logout</a>
            </div>
        </div>
    </header>
    <div class="main-container">
        <div class="sidebar">
            <h3><a href="../dashboard.php">User Dashboard</a></h3>
            <ul>
                <li class='selected'><a href="view_reports.php">View Reports</a></li>
                <li><a href="../scams/report_scam.php">Report New Scam</a></li>
                <li><a href="../content/educational_content.php">Educational Content</a></li>
            </ul>
        </div>
        <div class="content">
            <h2>Your Reports</h2>
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
