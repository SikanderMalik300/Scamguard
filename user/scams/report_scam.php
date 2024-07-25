<?php
include '../../db_connect.php';
include '../../session.php';
include 'ScamReport.php';

checkSession();

$user = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
    $stmt->close();
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scam_title = filter_input(INPUT_POST, 'scam_title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $evidence_files = $_FILES['evidence'];

    $scamReport = new ScamReport($conn, $user);
    $result_message = $scamReport->submitReport($scam_title, $description, $evidence_files);

    if ($result_message === 'Report submitted successfully!') {
        $success_message = $result_message;
    } else {
        $error_message = $result_message;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report a Scam</title>
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

.selected {
    background-color: #e6e6e6;
}

.content h2 {
    color: #1e90ff;
    margin-top: 0;
    font-size: 24px; /* Larger font size for headings */
}

h3 a {
    text-decoration: none;
    color: #1e90ff;
}

        .form-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
            box-sizing: border-box;
            margin: 20px auto; /* Center the form container */
        }

        .form-container h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 700;
        }

        .form-container label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }

        .form-container input[type="text"],
        .form-container textarea,
        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
        }

        .form-container textarea {
            resize: vertical;
            min-height: 150px;
        }

        .form-container button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #2980b9;
        }

        .response-message {
            font-size: 16px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
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
            <span><?php echo htmlspecialchars($user['name']); ?></span>
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
                <li><a href="../reports/view_reports.php">View Reports</a></li>
                <li class='selected'><a href="report_scam.php">Report New Scam</a></li>
                <li><a href="../content/educational_content.php">Educational Content</a></li>
            </ul>
        </div>
        <div class="content">
            <div class="form-container">
                <h2>Submit a Scam Report</h2>
                <?php if ($success_message): ?>
                    <div class="response-message success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="response-message error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <form method="post" enctype="multipart/form-data">
                    <label for="scam_title">Scam Title:</label>
                    <input type="text" id="scam_title" name="scam_title" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>

                    <label for="evidence">Evidence Files:</label>
                    <input type="file" id="evidence" name="evidence[]" multiple>

                    <button type="submit">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
