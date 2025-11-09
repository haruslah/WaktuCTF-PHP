<?php
require("./conf.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user'];
$message = "";

// create team
if (isset($_POST['create_team'])) {
    $team_name = trim($_POST['team_name']);

    if (!empty($team_name)) {
        $check = "SELECT team_id FROM teams WHERE team_name='$team_name'";
        $check_result = $conn->query($check);

        if ($check_result->num_rows > 0) {
            $message = "Team name already taken.";
        } else {
            $insert = "INSERT INTO teams (team_name, created_by) VALUES ('$team_name', '$user_id')";
            if ($conn->query($insert) === TRUE) {
                $team_id = $conn->insert_id;
                $conn->query("INSERT INTO team_members (team_id, user_id) VALUES ('$team_id', '$user_id')");
                $message = "Team created successfully!";
            } else {
                $message = "Error creating team.";
            }
        }
    } else {
        $message = "Please enter a team name.";
    }
}

// join team
if (isset($_POST['join_team'])) {
    $team_name = trim($_POST['team_name_join']);

    $query = "SELECT team_id FROM teams WHERE team_name='$team_name'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $team_id = $row['team_id'];

        $check = "SELECT * FROM team_members WHERE team_id='$team_id' AND user_id='$user_id'";
        $res = $conn->query($check);

        if ($res->num_rows > 0) {
            $message = "You are already in this team.";
        } else {
            $conn->query("INSERT INTO team_members (team_id, user_id) VALUES ('$team_id', '$user_id')");
            $message = "Successfully joined the team!";
        }
    } else {
        $message = "Team not found.";
    }
}

// leave team
if (isset($_POST['leave_team'])) {
    $team_id = $_POST['team_id'];

    // delete from team_members
    $delete = "DELETE FROM team_members WHERE team_id='$team_id' AND user_id='$user_id'";
    if ($conn->query($delete) === TRUE) {
        $message = "You have left the team.";
    } else {
        $message = "Error leaving the team.";
    }

    // optional cleanup: if user was the creator and no one else left, delete the team
    $check_members = $conn->query("SELECT * FROM team_members WHERE team_id='$team_id'");
    if ($check_members->num_rows == 0) {
        $conn->query("DELETE FROM teams WHERE team_id='$team_id'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teams - WaktuCTF</title>
    <link rel="stylesheet" href="style.css">
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

    <section class="teams">
        <div class="team-card">
            <h1>TEAM MANAGEMENT</h1>
            <?php if (!empty($message)) echo "<p><b>$message</b></p>"; ?>

            <h3>Create a New Team</h3>
            <form method="POST">
                <input type="text" name="team_name" placeholder="Enter team name" required>
                <button type="submit" name="create_team">Create Team</button>
            </form>

            <h3 style="margin-top: 20px;">Join an Existing Team</h3>
            <form method="POST">
                <input type="text" name="team_name_join" placeholder="Enter team name" required>
                <button type="submit" name="join_team">Join Team</button>
            </form>
        </div>

        <div class="team-list">
            <h3>Your Teams</h3>
            <?php
            $query = "SELECT t.team_id, t.team_name, t.created_at, t.created_by
                      FROM teams t
                      JOIN team_members m ON t.team_id = m.team_id
                      WHERE m.user_id = '$user_id'";
            $teams = $conn->query($query);

            if ($teams->num_rows > 0) {
                while ($team = $teams->fetch_assoc()) {
                    echo "<div class='team-item'>";
                    echo "<p><strong>" . htmlspecialchars($team['team_name']) . "</strong> - Joined on " . date("d/m/Y", strtotime($team['created_at'])) . "</p>";

                    echo "<form method='POST' style='display:inline;'>
                            <input type='hidden' name='team_id' value='" . $team['team_id'] . "'>
                            <button type='submit' name='leave_team' onclick='return confirm(\"Are you sure you want to leave this team?\");' class='submit-leave'>Leave Team</button>
                          </form>";

                    echo "</div>";
                }
            } else {
                echo "<p>You have not joined any team yet.</p>";
            }
            ?>
        </div>
    </section>
</body>
</html>
