<?php
require_once '../../db_connect.php';
require_once '../../session.php';
require_once 'UserManager.php';

checkSession();

$userManager = new UserManager($conn);
$users = $userManager->getUsers();

$status = isset($_GET['status']) ? $_GET['status'] : '';

function getStatusMessage($status) {
    switch ($status) {
        case 'deleted':
            return 'User has been successfully deleted.';
        case 'notfound':
            return 'User not found.';
        case 'foreignkey':
            return 'Cannot delete user because they have associated records.';
        case 'error':
            return 'An error occurred while deleting the user.';
        case 'missingid':
            return 'No user ID specified.';
        default:
            return '';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        .action-btn {
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #4CAF50;
            padding: 8px 8px;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }

        .delete-btn {
            background-color: #f44336;
            padding: 5px 8px;
        }

        .delete-btn:hover {
            background-color: #e53935;
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

        .icon-small {
            font-size: 14px; /* Adjust the size as needed */
            margin-right: 5px; /* Adjust spacing if needed */
        }

        .action-btn i {
            vertical-align: middle; /* Align icons with text */
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #ffffff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            color: #1e90ff;
        }

        .modal-header .close {
            background-color: #f44336;
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 4px;
        }

        .modal-header .close:hover {
            background-color: #e53935;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-body input[type="text"], .modal-body input[type="email"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modal-body button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        .modal-body button:hover {
            background-color: #45a049;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .modal-content {
                width: 90%;
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
        <aside class="sidebar">
            <h3><a href="../admin_dashboard.php">Admin Dashboard</a></h3>
            <ul>
                <li><a href="../reports/view_all_reports.php">View All Reports</a></li>
                <li class='selected'><a href="manage_users.php">Manage Users</a></li>
                <li><a href="../educational_content/add_educational_content.php">Add Educational Content</a></li>
                <li><a href="../educational_content/manage_educational_content.php">Manage Educational Content</a></li>
            </ul>
        </aside>
        <div class="content">
            <h2>User List</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Country</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['mobile']); ?></td>
                                <td><?php echo htmlspecialchars($user['address']); ?></td>
                                <td><?php echo htmlspecialchars($user['country']); ?></td>
                                <td>
                                    <button class="action-btn edit-btn" onclick="editUser(<?php echo htmlspecialchars($user['id']); ?>)">
                                        <i class="fas fa-edit icon-small"></i>
                                    </button>
                                    <a href="delete_user.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash-alt icon-small"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Update Form -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit User</h2>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="updateForm" action="update_user.php" method="post">
                    <input type="hidden" name="id" id="userId">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                    <br>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <br>
                    <label for="mobile">Mobile</label>
                    <input type="text" id="mobile" name="mobile" required>
                    <br>
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                    <br>
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" required>
                    <br>
                    <label for="password">Password</label>
                    <input type="text" id="password" name="password">
                    <br>
                    <button type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">Action completed successfully!</div>

    <?php if ($status): ?>
        <div class="status-message">
            <?php echo htmlspecialchars(getStatusMessage($status)); ?>
        </div>
    <?php endif; ?>

    <script>
        function closeModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        function showToast(message) {
            var toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = "toast show";
            setTimeout(function() { toast.className = toast.className.replace(" show", ""); }, 3000);
        }

        function editUser(userId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_user.php?id=' + userId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var user = JSON.parse(xhr.responseText);
                    document.getElementById('userId').value = user.id;
                    document.getElementById('name').value = user.name;
                    document.getElementById('email').value = user.email;
                    document.getElementById('mobile').value = user.mobile;
                    document.getElementById('address').value = user.address;
                    document.getElementById('country').value = user.country;
                    document.getElementById('updateModal').style.display = 'flex';
                } else {
                    showToast('Failed to fetch user data.');
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>