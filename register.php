<?php
include 'db_connect.php';
include 'session.php';

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($name, $email, $password, $mobile, $address, $country, $dob, $gender, $type = 'user') {
        if ($this->emailExists($email)) {
    return 'User already exists! <a href="login.php">Login here</a>';
}


        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, mobile, address, country, dob, gender, type)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssss", $name, $email, $passwordHash, $mobile, $address, $country, $dob, $gender, $type);

        if ($stmt->execute()) {
    return 'You are successfully registered! <a href="login.php">Login here</a>';
} else {
    return 'Error: ' . $stmt->error;
}

    }

    private function emailExists($email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);
    $message = $user->register(
        $_POST['name'],
        $_POST['email'],
        $_POST['password'],
        $_POST['mobile'],
        $_POST['address'],
        $_POST['country'],
        $_POST['dob'],
        $_POST['gender']
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
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
            max-width: 900px;
            width: 100%;
            margin: 100px 20px 20px; /* Adjusted top margin for fixed header */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .form-section {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .left-side, .right-side {
            width: 100%;
            padding: 10px;
        }

        .left-side {
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .right-side {
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        h2 {
            color: #1e90ff; /* Blue color */
            margin-bottom: 20px;
            font-size: 24px;
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }

        label {
            margin-bottom: 5px;
            display: block;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px; /* Adjust as needed */
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
            margin-top: 10px;
        }

        button:hover {
            background-color: #4682b4; /* Darker blue on hover */
        }

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
            font-family: 'Poppins', sans-serif;
        }

        .toast.success {
            background-color: #28a745; /* Green for success */
        }

        .toast.error {
            background-color: #dc3545; /* Red for error */
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

        .gender-radio {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .gender-radio input[type="radio"] {
            margin-right: 5px;
        }

        .gender-radio label {
            margin-right: 15px;
        }

        /* Responsive Styles */
        @media (min-width: 768px) {
            .form-section {
                flex-direction: row;
            }

            .left-side, .right-side {
                width: 50%;
                padding: 20px;
            }

            .left-side {
                border-right: 1px solid #ddd;
            }

            .right-side {
                border-left: 1px solid #ddd;
            }
        }

        @media (max-width: 767px) {
            .container {
                margin-top: 80px; /* Space for the fixed header */
            }

            .form-section {
                flex-direction: column;
            }

            .left-side, .right-side {
                width: 100%;
                padding: 10px;
                border: none;
            }

            .left-side {
                border-bottom: 1px solid #ddd;
            }

            .right-side {
                border-top: 1px solid #ddd;
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
        <div class="register-link">
            <p><?php echo $message; ?></p>
        </div>
        <h2>Registration Form</h2>
        <div class="form-section">
            <div class="left-side">
                <form id="registrationForm" action="register.php" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="mobile">Mobile:</label>
                    <input type="text" id="mobile" name="mobile" required>
            </div>
            <div class="right-side">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" required>
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>
                    <div class="gender-radio">
                        <label for="male"><input type="radio" id="male" name="gender" value="male" required> Male</label>
                        <label for="female"><input type="radio" id="female" name="gender" value="female" required> Female</label>
                        <label for="other"><input type="radio" id="other" name="gender" value="other" required> Other</label>
                    </div>
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
        <div class="register-link">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    </div>
</body>
</html>
