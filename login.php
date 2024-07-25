<?php
include 'session.php'; // Includes the database connection
include 'db_connect.php'; // Includes the connection logic

class Auth {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $password) {
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT id, password, type FROM users WHERE email = '$email'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                return $row['type'];
            }
        }
        return false;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $auth = new Auth($conn);
    $user_type = $auth->login($email, $password);

    if ($user_type) {
        if ($user_type == 'admin') {
            header('Location: admin/admin_dashboard.php');
        } else {
            header('Location: user/dashboard.php');
        }
        exit();
    } else {
        $invalid_login = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            align-items: center;
            justify-content: center;
        }

        header {
            background-color: #1e90ff; /* Blue color */
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        header img {
            height: 40px;
            margin-right: 10px;
        }

        header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            margin: 0;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .image-section {
            background: url('assets/login.svg') no-repeat center center;
            background-size: cover;
            width: 100%;
            height: 200px;
            display: block;
        }

        .form-section {
            width: 100%;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            color: #1e90ff; /* Blue color */
            font-size: 24px;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }

        label {
            margin-bottom: 5px;
            display: block;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #1e90ff; /* Blue color */
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
        }

        button:hover {
            background-color: #4682b4; /* Darker blue on hover */
        }

        .register-link {
            margin-top: 20px;
            text-align: center;
        }

        .register-link a {
            color: #1e90ff; /* Blue color */
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Toast Notification Styles */
        .toast {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #333;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .toast button {
            background: #1e90ff;
            border: none;
            color: white;
            padding: 10px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .toast button:hover {
            background: #4682b4;
        }

        /* Responsive Styles */
        @media (min-width: 768px) {
            .container {
                flex-direction: row;
            }

            .image-section {
                display: block;
                height: 300px;
            }

            .form-section {
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .image-section {
                display: none;
            }

            .form-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="ScamGuard Logo">
        <h1>ScamGuard</h1>
    </header>
    <div class="container">
        <div class="image-section">
            <!-- Background image section -->
        </div>
        <div class="form-section">
            <h2>User Login</h2>
            <?php if (isset($invalid_login) && $invalid_login === true) { ?>
                <div class="error-message">Invalid email or password.</div>
            <?php } ?>
            <form action="login.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register now</a></p>
            </div>
        </div>
    </div>
</body>
</html>
