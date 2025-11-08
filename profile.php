<?php 
require("./conf.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user'];

// update profile
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!empty($username) && !empty($email)) {
        // update the data
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id='$userID'";
        if ($conn->query($sql) === TRUE) {
            $message = "Profile updated successfully!";
        } else {
            $message = "Error updating profile: " . $conn->error;
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

// get user data
$sql = "SELECT * FROM users WHERE id='$userID'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
}

// get team name
// $team_sql = "SELECT t.team_name 
//              FROM teams t
//              INNER JOIN team_members tm ON tm.team_id = t.team_id
//              WHERE tm.user_id = '$userID'
//              LIMIT 1";
// $team_result = $conn->query($team_sql);

// if ($team_result && $team_result->num_rows > 0) {
//     $team_row = $team_result->fetch_assoc();
//     $team_name = $team_row['team_name'];
// } else {
//     $team_name = "Unassigned";
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" href="/images/faviconwctf.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <div class="left">
            <h1><a href="index.php">WaktuCTF</a></h1>
        </div>
        <div class="right">
            <p><a href="about.php">About</a></p>
            <?php 
                if (isset($_SESSION['user'])) {
                    echo '<p><a href="profile.php">Profile</a></p>';
                    echo '<p><a href="team.php">Team</a></p>';
                    echo '<p><a href="logout.php">Logout</a></p>';
                } else {
                    echo '<p><a href="login.php">Login</a></p>';
                }
            ?>
        </div>
    </div>

    <section class="profile">
        <div class="profile-card">
            <h2>User Profile</h2>

            <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>

            <?php if (isset($row)): ?>
                <form method="POST">
                    <label>Username:</label><br>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>"><br><br>

                    <label>Email:</label><br>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"><br><br>

                    <p><strong>Join Date:</strong> <?php echo date("d/m/Y", strtotime($row['created_at'])); ?></p>
                    <!-- <p><strong>Team:</strong> <?php echo htmlspecialchars($team_name); ?></p> -->
                    <p><strong>CTF Participated:</strong> <?php echo htmlspecialchars($row['ctf_joined']); ?></p>

                    <input type="submit" name="update" value="Update Profile" class="submit-update">
                </form>
            <?php else: ?>
                <p>User data not found. Please <a href="login.php">log in</a>.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
