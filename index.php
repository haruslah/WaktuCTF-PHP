<?php 
require("./conf.php");

// Get current date
$today = date('Y-m-d');

// Fetch upcoming and past CTFs
$upcoming_sql = "SELECT * FROM ctf_events WHERE date >= '$today' ORDER BY date ASC";
$past_sql = "SELECT * FROM ctf_events WHERE date < '$today' ORDER BY date DESC";

$upcoming_result = $conn->query($upcoming_sql);
$past_result = $conn->query($past_sql);

$search_result = null;
if (isset($_GET['submit'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM ctf_events WHERE title LIKE '%$search%' OR format LIKE '%$search%' ORDER BY date ASC";
    $search_result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta title="viewport" content="width=device-width, initial-scale=1.0">
    <title>WaktuCTF</title>
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

    <header class="hero">
        <h2>Hack, Learn, and Win!</h2>
        <p>We connect players, teams, and competitions worldwide.</p>
    </header>

    <section class="search">
        <h3>Search CTF</h3>
        <form method="GET">
            <input type="text" name="search" placeholder="Search for CTF.." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <input type="submit" value="Search" name="submit" class="submit-search">
        </form>
        <?php if ($search_result !== null): ?>
            <div class="ctf-list">
                <?php if ($search_result->num_rows > 0): ?>
                    <?php while ($row = $search_result->fetch_assoc()): ?>
                        <div class="ctf-card">
                            <h4><?= htmlspecialchars($row['title']); ?></h4>
                            <p>Date: <?= date("d/m/Y", strtotime($row['date'])); ?></p>
                            <p>Format: <?= htmlspecialchars($row['format']); ?></p>
                            <a href="ctf-register.php?ctf_id=<?= $row['ctf_id']; ?>" class="register-btn">Play</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No CTFs found matching your search.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="upcoming">
        <h3>Upcoming CTFs</h3>
        <div class="ctf-list">
            <?php if ($upcoming_result && $upcoming_result->num_rows > 0): ?>
                <?php while ($row = $upcoming_result->fetch_assoc()): ?>
                    <div class="ctf-card">
                        <h4><?= htmlspecialchars($row['title']); ?></h4>
                        <p>Date: <?= date("d/m/Y", strtotime($row['date'])); ?></p>
                        <p>Format: <?= htmlspecialchars($row['format']); ?></p>
                        <a href="ctf-register.php?ctf_id=<?= $row['ctf_id']; ?>" class="register-btn">Play</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No upcoming CTFs available right now.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="past">
        <h3>Past CTFs</h3>
        <div class="ctf-list">
            <?php if ($past_result && $past_result->num_rows > 0): ?>
                <?php while ($row = $past_result->fetch_assoc()): ?>
                    <div class="ctf-card">
                        <h4><?= htmlspecialchars($row['title']); ?></h4>
                        <p>Date: <?= date("d/m/Y", strtotime($row['date'])); ?></p>
                        <p>Format: <?= htmlspecialchars($row['format']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No past CTF</p>
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
