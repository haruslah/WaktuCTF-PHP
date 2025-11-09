<?php
require("./conf.php");

if(isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if(isset($_POST['submit'])  && $_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "<script>
            alert('Wrong password');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT username FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if($result && $result->num_rows > 0) {
        echo "<script>
            alert('Username already exist');
            window.location.href = 'register.php';
        </script>";
    }

    else {
        $sql = "INSERT INTO users (username, email, password_hash, ctf_joined) VALUES ('$username', '$email', '$hashed_password', 0)";
        $result = $conn->query($sql);

        if($result) {
            echo "<script>
                    alert('Your account registered successfully. Please login');
                    window.location.href = 'login.php';
            </script>";
        }

        else {
            echo "<script>
                alert('Error on registration');
                window.location.href = 'register.php';
            </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registration</title>
    <link rel="icon" href="<?php echo BASE_URL; ?>/images/faviconwctf.png" type="image/png">
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
    <section class="register">
        <div class="form-card">
            <h2>Create Your Account</h2>
            <form id="registerForm" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>

                <div class="button-group">
                    <button type="submit" name="submit" class="submit">Submit</button>
                    <button type="reset" class="clear">Clear</button>
                </div>

                <p class="register-link">
                    Already have an account? <a href="login.php">Login Here</a>
                </p>
            </form>
        </div>
    </section>
</body>
</html>
