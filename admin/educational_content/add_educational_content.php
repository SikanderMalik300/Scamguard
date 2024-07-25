<?php
include '../../db_connect.php';
include '../../session.php';
checkSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $type = htmlspecialchars($_POST['type']);
    $video_url = htmlspecialchars($_POST['video_url']);

    // Prepare SQL query to insert data
    $stmt = $conn->prepare("INSERT INTO educational_content (title, content, type, video_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $content, $type, $video_url);

    if ($stmt->execute()) {
        $message = "Content added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Educational Content</title>
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
            flex-shrink: 0;
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

        .selected {
            background-color: #e6e6e6;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #f0f8ff;
            overflow-y: auto;
            box-sizing: border-box;
            max-width: 600px;
            margin: 0 auto;
        }

        .content h2 {
            color: #1e90ff;
            margin-top: 0;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group button {
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #007acc;
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
            <h3><a href="admin_dashboard.php">Admin Dashboard</a></h3>
            <ul>
                <li><a href="../reports/view_all_reports.php">View All Reports</a></li>
                <li><a href="../users/manage_users.php">Manage Users</a></li>
                <li class='selected'><a href="add_educational_content.php">Add Educational Content</a></li>
                <li><a href="manage_educational_content.php">Manage Educational Content</a></li>
            </ul>
        </div>
        <main class="content">
            <h2>Add New Educational Content</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="type">Type:</label>
                    <input type="text" id="type" name="type" required>
                </div>
                <div class="form-group">
                    <label for="video_url">Video URL (optional):</label>
                    <input type="text" id="video_url" name="video_url">
                </div>
                <div class="form-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
            <?php if (isset($message)) { ?>
                <div class="toast show"><?php echo $message; ?></div>
            <?php } ?>
        </main>
    </div>
</body>
</html>
