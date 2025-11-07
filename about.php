<?php 
require('./conf.php')
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
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

    <section class="about">
        <div class="center">
            <h2>About Us</h2>
            <p>
                <b>WaktuCTF</b> is a platform built by students, for students to bring together Capture The Flag (CTF)
                 events, teams, and players in one place. Inspired by the spirit of hacking and problem-solving, 
                 it serves as a central hub where communities can discover upcoming competitions, track results, 
                 and monitor their progress over time.
            </p>
            <p>
                Whether you’re just starting out or a seasoned player climbing the leaderboard, 
                <b>WaktuCTF</b> keeps you connected to the global CTF scene.
                <br><br>
                Our goal is to make cybersecurity competitions more accessible, organized,
                 and exciting for everyone. Join us, and let’s <b>Hack, Learn, and Win</b> together!
            </p>
        </div>
    </section>
</body>
</html>