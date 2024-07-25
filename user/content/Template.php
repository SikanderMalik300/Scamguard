<?php
class Template {
    private $user;
    private $contents;

    public function __construct($user, $contents) {
        $this->user = $user;
        $this->contents = $contents;
    }

    public function render() {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Educational Content</title>
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
            position: relative;
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
            transition: width 0.3s ease;
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

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: calc(25% - 20px); /* Three cards per row with gap */
            box-sizing: border-box;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .thumbnail {
            width: 100%;
            height: auto;
        }

        .card-content {
            padding: 15px;
        }

        .card-title {
            font-size: 20px;
            color: #333;
            margin: 0 0 10px;
        }

        .card-description {
            font-size: 14px;
            color: #666;
        }

        footer {
            background-color: #1e90ff;
            color: white;
            text-align: center;
            padding: 15px 25px;
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

        h3 a {
            text-decoration: none;
            color: #1e90ff;
        }

        .selected {
            background-color: #e6e6e6;
        }

        @media (max-width: 768px) {
            .card {
                width: calc(50% - 20px); /* Two cards per row on smaller screens */
            }
        }

        @media (max-width: 480px) {
            .card {
                width: 100%; /* Full width on very small screens */
            }
        }

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
                    <div class="dropdown-toggle">
                        <span><?php echo htmlspecialchars($this->user['name']); ?></span>
                        <i class="fas fa-chevron-down icon"></i>
                    </div>
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
                        <li><a href="../scams/report_scam.php">Report New Scam</a></li>
                        <li class='selected'><a href="educational_content.php" class="selected">Educational Content</a></li>
                    </ul>
                </div>
                <div class="content">
                    <h2>Educational Content</h2>
                    <div class="card-container">
                        <?php foreach ($this->contents as $content): ?>
                            <div class="card">
                                <?php if (!empty($content['video_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($content['video_url']); ?>" target="_blank">
                                        <img src="https://img.youtube.com/vi/<?php echo $this->extractYouTubeID($content['video_url']); ?>/0.jpg" alt="Video Thumbnail" class="thumbnail">
                                    </a>
                                <?php endif; ?>
                                <div class="card-content">
                                    <h3 class="card-title"><?php echo htmlspecialchars($content['title']); ?></h3>
                                    <p class="card-description"><?php echo htmlspecialchars($content['content']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        echo ob_get_clean();
    }

    private function extractYouTubeID($url) {
        parse_str(parse_url($url, PHP_URL_QUERY), $vars);
        return $vars['v'];
    }
}
?>
