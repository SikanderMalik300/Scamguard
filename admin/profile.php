<?php
include '../db_connect.php';
include '../session.php';
checkSession();

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Check for success message in URL parameters
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
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

        .main-container {
            padding: 20px;
            box-sizing: border-box;
            flex: 1;
        }

        .content {
            max-width: 700px; /* Width of the form */
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #1e90ff;
            color: white;
            padding: 10px 12px;
            border-radius: 30%;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #00bfff;
        }

        .content h2 {
            color: #1e90ff;
            margin-top: 0;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
        }

        input[type="text"],
        input[type="email"],
        textarea,
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[readonly] {
    background-color: #f5f5f5; /* Light gray background */
    border: 1px solid #ddd; /* Same border as other inputs */
    cursor: not-allowed; /* Indicate that the field is not editable */
    color: #888; /* Gray text color */
    font-style: italic; /* Italic text to suggest non-editability */
}

input[readonly]::placeholder {
    color: #888; /* Placeholder color */
}

        button {
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #00bfff;
        }

        .message {
            margin: 20px 0;
            color: #1e90ff;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 10px;
            }

            .content {
                max-width: 100%;
                padding: 10px;
            }
        }
        .toast {
            visibility: hidden;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #333;
            color: #fff;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 16px;
            z-index: 1000;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        .toast.hide {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
    </style>
        
</head>
<body>
    <header>
        <h1 class="header-title">ScamGuard</h1>
        <img src="../assets/logo.png" alt="ScamGuard Logo">
    </header>

    <div class="main-container">
        <div class="content">
            <a href="admin_dashboard.php" class="back-button">&#9664;</a>
            <h2>Update Profile</h2>
            <form action="update_profile.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>

                <label for="address">Address:</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>

                <label for="country">Country:</label>
                <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>" required>

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter new password (optional)">

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- Toast notification -->
    <div id="toast" class="toast <?php echo $message ? 'show' : ''; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>

    <script>
        // JavaScript to hide the toast message after 3 seconds
        document.addEventListener('DOMContentLoaded', function () {
            var toast = document.getElementById('toast');
            if (toast.classList.contains('show')) {
                setTimeout(function () {
                    toast.classList.remove('show');
                    toast.classList.add('hide');
                }, 3000); // Duration in milliseconds (3 seconds)
            }
        });
    </script>
</body>
</html>
