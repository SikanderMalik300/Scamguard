<?php
include '../../db_connect.php';
include '../../session.php';
checkSession();

class EducationalContentManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function fetchAllContent() {
        $sql_content = "SELECT id, title, content, video_url FROM educational_content";
        $result_content = $this->conn->query($sql_content);
        return $result_content->fetch_all(MYSQLI_ASSOC);
    }
}

class HtmlHelper {
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

$manager = new EducationalContentManager($conn);
$contents = $manager->fetchAllContent();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Educational Content</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .selected {
            background-color: #e6e6e6;
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

        .actions {
            text-align: center;
        }

        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 2px;
            color: #fff;
        }

        .btn-edit {
            background-color: #4CAF50;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-delete {
            background-color: #f44336;
        }

        .btn-delete:hover {
            background-color: #e53935;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 8px;
            margin: 15% auto;
            padding: 20px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            position: relative;
        }

        .modal-header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .modal-header h2 {
            margin: 0;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #888;
        }

        .modal-close:hover {
            color: #555;
        }

        .modal-body {
            margin: 10px 0;
        }

        .modal-footer {
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .modal-footer button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 5px;
        }

        .modal-footer .btn-update {
            background-color: #4CAF50;
            color: white;
        }

        .modal-footer .btn-update:hover {
            background-color: #45a049;
        }

        .modal-footer .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .modal-footer .btn-delete:hover {
            background-color: #e53935;
        }

        .modal-footer .btn-cancel {
            background-color: #ddd;
            color: black;
        }

        .modal-footer .btn-cancel:hover {
            background-color: #ccc;
        }

        .modal-body label {
        display: block;
        margin: 10px 0 5px;
        font-weight: bold;
    }

    .modal-body input[type="text"], 
    .modal-body textarea {
        width: 100%;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }

    .modal-body textarea {
        resize: vertical;
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
            <div class="dropdown-toggle">
                <span>Admin</span>
            </div>
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
                <li><a href="../educational_content/add_educational_content.php">Add Educational Content</a></li>
                <li class="selected"><a href="manage_educational_content.php">Manage Educational Content</a></li>
            </ul>
        </div>
        <div class="content">
            <h2>Manage Educational Content</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Video URL</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contents as $content) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($content['id']); ?></td>
                                <td><?php echo htmlspecialchars($content['title']); ?></td>
                                <td><?php echo htmlspecialchars($content['content']); ?></td>
                                <td><?php echo htmlspecialchars($content['video_url']); ?></td>
                                <td class="actions">
                                    <button class="btn-edit" data-id="<?php echo $content['id']; ?>" data-title="<?php echo htmlspecialchars($content['title']); ?>" data-content="<?php echo htmlspecialchars($content['content']); ?>" data-video_url="<?php echo htmlspecialchars($content['video_url']); ?>">Edit</button>
                                    <button class="btn-delete" data-id="<?php echo $content['id']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-close">&times;</span>
                <h2>Edit Educational Content</h2>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="update_content.php">
                    <input type="hidden" name="id" id="modal-id">
                    <label for="modal-title">Title:</label>
                    <input type="text" name="title" id="modal-title" required><br><br>
                    <label for="modal-content">Content:</label>
                    <textarea name="content" id="modal-content" rows="4" required></textarea><br><br>
                    <label for="modal-video_url">Video URL:</label>
                    <input type="text" name="video_url" id="modal-video_url" required><br><br>
                    <div class="modal-footer">
                        <button type="submit" name="update" class="btn-update">Update</button>
                        <button type="button" class="btn-cancel" id="modal-cancel">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        var editModal = document.getElementById('editModal');
        var editBtns = document.querySelectorAll('.btn-edit');
        var closeBtns = document.querySelectorAll('.modal-close');
        var cancelBtns = document.querySelectorAll('.btn-cancel');

        // Open Edit Modal
        editBtns.forEach(btn => {
            btn.onclick = function() {
                var id = this.getAttribute('data-id');
                var title = this.getAttribute('data-title');
                var content = this.getAttribute('data-content');
                var videoUrl = this.getAttribute('data-video_url');
                document.getElementById('modal-id').value = id;
                document.getElementById('modal-title').value = title;
                document.getElementById('modal-content').value = content;
                document.getElementById('modal-video_url').value = videoUrl;
                editModal.style.display = 'flex';
            }
        });

        // Close Modal
        closeBtns.forEach(btn => {
            btn.onclick = function() {
                editModal.style.display = 'none';
            }
        });

        cancelBtns.forEach(btn => {
            btn.onclick = function() {
                editModal.style.display = 'none';
            }
        });

        // Delete content
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this content?')) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete_content.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('An error occurred while deleting the content.');
                        }
                    };
                    xhr.send('id=' + encodeURIComponent(id));
                }
            });
        });
    </script>
</body>
</html>