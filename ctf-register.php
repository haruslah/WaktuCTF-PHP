<?php 
require("./conf.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['user'];
$message = "";

// Get CTF ID
if (isset($_GET['ctf_id'])) {
    $ctf_id = $_GET['ctf_id'];
    $sql = "SELECT * FROM ctf_events WHERE ctf_id = $ctf_id";
    $result = $conn->query($sql);
    $ctf = $result->fetch_assoc();
}

// Get user info
$user_sql = "SELECT username, email, ctf_joined FROM users WHERE id=$userID";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

// Get all teams that the user joined
$team_sql = "SELECT t.team_name FROM teams t 
             JOIN team_members m ON t.team_id = m.team_id 
             WHERE m.user_id = $userID";
$team_result = $conn->query($team_sql);

// Handle registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team = $_POST['team'];
    $experience = $_POST['experience'];
    $notify = isset($_POST['notify']) ? implode(", ", $_POST['notify']) : "";
    $notes = $_POST['notes'];

    $check_sql = "SELECT * FROM user_ctf WHERE user_id=$userID AND ctf_id=$ctf_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $message = "You already registered for this CTF!";
    } else {
        $insert_sql = "INSERT INTO user_ctf (user_id, ctf_id, team_name, experience, notify, notes, joined_at) 
                       VALUES ($userID, $ctf_id, '$team', '$experience', '$notify', '$notes', NOW())";
        $conn->query($insert_sql);

        $update_sql = "UPDATE users SET ctf_joined = ctf_joined + 1 WHERE id=$userID";
        $conn->query($update_sql);

        $message = "Successfully registered for " . htmlspecialchars($ctf['title']) . "!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta title="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - WaktuCTF</title>
    <link rel="icon" href="<?php echo BASE_URL; ?>/images/faviconwctf.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;700&display=swap" rel="stylesheet">
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

<section class="ctf-register">
    <div class="form-card">
        <?php if (isset($ctf)): ?>
        <h2>Register for <?= htmlspecialchars($ctf['title']); ?></h2>
        <hr>
        <?php if ($message): ?>
            <p style="color: green;"><?= $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" value="<?= $user['username']; ?>" readonly>

            <label>Email:</label>
            <input type="email" name="email" value="<?= $user['email']; ?>" readonly>

            <label>Team Name:</label>
            <select name="team" required>
                <option value="">-- Select your team --</option>
                <?php
                if ($team_result->num_rows > 0) {
                    while ($team_row = $team_result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($team_row['team_name']) . "'>" . htmlspecialchars($team_row['team_name']) . "</option>";
                    }
                } else {
                    echo "<option value=''>You are not in any team</option>";
                }
                ?>
            </select>

            <label>Experience Level:</label>
            <div class="radio-group">
                <input type="radio" id="beginner" name="experience" value="beginner" required>
                <label for="beginner">Beginner</label>

                <input type="radio" id="intermediate" name="experience" value="intermediate">
                <label for="intermediate">Intermediate</label>

                <input type="radio" id="advanced" name="experience" value="advanced">
                <label for="advanced">Advanced</label>
            </div>

            <label>Notify Me Via:</label>
            <div class="checkbox-group">
                <input type="checkbox" id="email_notify" name="notify[]" value="Email">
                <label for="email_notify">Email</label>

                <input type="checkbox" id="discord_notify" name="notify[]" value="Discord">
                <label for="discord_notify">Discord</label>

                <input type="checkbox" id="whatsapp_notify" name="notify[]" value="Whatsapp">
                <label for="whatsapp_notify">Whatsapp</label>
            </div>

            <label>Additional Notes:</label>
            <textarea name="notes" rows="4" placeholder="Anything you'd like to add..."></textarea>

            <div class="button-group">
                <button type="submit" class="submit">Submit</button>
                <button type="reset" class="clear">Clear</button>
            </div>
        </form>
        <?php else: ?>
            <p>CTF not found. <a href="index.php">Go back</a></p>
        <?php endif; ?>
    </div>
</section>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 WaktuCTF. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
