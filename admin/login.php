<?php
session_start();
require_once '../includes/db.php'; // DB connection file path

if (isset($_POST['login'])) {
    // Collecting email and password from form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to securely fetch admin data using email
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Verify using password_verify (assuming registration stored hashed password)
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['email'];
            header("Location: dashbord.php"); // ideally, you might want to rename this to dashboard.php
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <!-- Optional: link to your stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php 
            if (isset($error)) {
                echo "<p style='color:red;'>$error</p>"; 
            }
        ?>
        <form method="post" action="">
            <div class="form-group">
                <label>Email:</label><br>
                <input type="email" name="email" required>
            </div>
            <br>
            <div class="form-group">
                <label>Password:</label><br>
                <input type="password" name="password" required>
            </div>
            <br>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
