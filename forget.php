<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    $check_query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Username exists, update the password
        $update_query = "UPDATE users SET password='$password' WHERE username='$username'";
        if (mysqli_query($conn, $update_query)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error updating password: " . mysqli_error($conn);
        }
    } else {
        // Username does not exist, insert a new record
        $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $insert_query)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error inserting new user: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rest Password</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Rest Password</h1>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post" action="">
        <label for="username">Your Username</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>
        
        <input type="submit" value="Submit">
    </form>
<p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
