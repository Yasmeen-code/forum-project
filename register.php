<?php
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt2 = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt2->bindParam(':email', $email);
    $stmt2->execute();
    $existingUser = $stmt2->fetch(PDO::FETCH_ASSOC);
    if ($existingUser) {
        echo "<script>alert('Email already exists! \\n Use another one.');</script>";
    }
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="register-style.css" />
</head>

<body>
    <div class="container">
        <div class="register-box">
            <form action="" method="post">
                <h2>Register</h2>

                <div class="input-box">
                    <input type="text" name="username" required />
                    <label>Full Name</label>
                </div>

                <div class="input-box">
                    <input type="email" name="email" required />
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <input type="password" name="password" required />
                    <label>Password</label>
                </div>

                <div class="input-box">
                    <input type="password" name="confirm_password" required />
                    <label>Confirm Password</label>
                </div>

                <button class="btn" type="submit">Register</button>

                <div class="login-link">
                    <a href="login.php">Already have an account?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>