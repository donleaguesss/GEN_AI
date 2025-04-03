<?php 
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    // Validate user credentials
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: home.php"); // Redirect to home page
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Login Container */
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }

        /* Heading */
        .login-container h2 {
            margin-bottom: 20px;
            color: #343a40;
        }

        /* Form Fields */
        .login-container label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .login-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Links */
        .login-links {
            margin-top: 15px;
        }

        .login-links a {
            text-decoration: none;
            color: #007bff;
            font-size: 14px;
        }

        .login-links a:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        /* Error Message */
        p.error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Responsive Design */
        @media (max-width: 400px) {
            .login-container {
                width: 90%;
            }
        }
    </style>

</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="login-links">
            <a href="#">Forgot Password?</a> | <a href="#">Sign Up</a>
        </div>
    </div>
</body>
</html>
