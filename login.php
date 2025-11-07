<?php
require("./conf.php");

if(isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    $usernameEmail = $_POST['usernameEmail'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$usernameEmail' OR email = '$usernameEmail'";
    $result = $conn->query($sql);

   if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password_hash'])) {
        $_SESSION['user'] = $row['id'];
        echo "<script>alert('Welcome back, {$row['username']}');</script>";
        header("Location: index.php");
        exit;
    } else {
        echo "<script>
            alert('Wrong username or password');
            window.location.href = 'login.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Wrong username or password');
        window.location.href = 'login.php';
    </script>";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="/images/faviconwctf.png" type="image/png">

    <link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="left">
            <h1><a href="index.php">WaktuCTF</a></h1>
        </div>
        <div class="right">
            <p><a href="about.php">About</a></p>
            <?php 
                if(!isset($_SESSION['user'])) {
                    echo '<p><a href="login.php">Login</a></p>';
                }
            ?>
        </div>
    </div>

    <div class="login-section">
        <div class="center">
            <form id="loginForm" method="POST">
                <h1><span>WaktuCTF</span></h1>
                <input type="text" id="usernameEmail" name="usernameEmail" placeholder="Username/Email" required><br>
                <input type="password" id="password" name="password" placeholder="Password" required><br>
                <input type="submit" name="submit" value="Login">
                <p class="register-link">
                No account? <a href="register.php">Register Here</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>