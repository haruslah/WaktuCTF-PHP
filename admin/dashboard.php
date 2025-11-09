<?php
require("./_guard.php");

// Handle create event
$createMsg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    $title  = trim($_POST['title'] ?? "");
    $date   = trim($_POST['date'] ?? "");
    $format = trim($_POST['format'] ?? "");

    if ($title !== "" && $date !== "" && $format !== "") {
        $stmt = $conn->prepare("INSERT INTO ctf_events (title, date, format) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $date, $format);
        if ($stmt->execute()) {
            $createMsg = "CTF event created.";
        } else {
            $createMsg = "Error creating event.";
        }
    } else {
        $createMsg = "Please fill in all fields.";
    }
}

// Fetch events (future & past)
$events = $conn->query("SELECT ctf_id, title, date, format FROM ctf_events ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - WaktuCTF</title>
  <link rel="icon" href="/images/faviconwctf.png" type="image/png">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

</head>
<body>
  <div class="navbar">
    <div class="left"><h1><a href="../index.php">WaktuCTF</a></h1></div>
    <div class="right">
      <p><a href="../about.php">About</a></p>
      <p><a href="./dashboard.php">Admin</a></p>
      <p><a href="../logout.php">Logout</a></p>
    </div>
  </div>

  <div class="admin-wrap">
    <div class="admin-header">
      <h2 style="font-family:'Bebas Neue',sans-serif;font-size:3rem;">Admin Dashboard</h2>
      <a class="btn" href="../index.php">Back to site</a>
    </div>

    <div class="admin-grid">
      <div class="card">
        <h3>Create CTF</h3>
        <?php if($createMsg) echo "<p class='muted'>$createMsg</p>"; ?>
        <form method="POST">
          <div class="row">
            <div><label>Title</label><input name="title" required></div>
            <div><label>Date</label><input type="date" name="date" required></div>
            <div><label>Format</label><input name="format" placeholder="Jeopardy/Attack-Defense/..." required></div>
          </div>
          <div style="margin-top:12px">
            <button class="btn" type="submit" name="create_event">Add Event</button>
          </div>
        </form>
      </div>

      <div class="card">
        <h3>All CTF Events</h3>
        <table>
          <thead><tr><th>Title</th><th>Date</th><th>Format</th><th>Actions</th></tr></thead>
          <tbody>
          <?php while($e = $events->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($e['title']) ?></td>
              <td><?= date("d/m/Y", strtotime($e['date'])) ?></td>
              <td><?= htmlspecialchars($e['format']) ?></td>
              <td class="actions">
                <a class="btn" href="./participants.php?ctf_id=<?= (int)$e['ctf_id'] ?>">View Participants</a>
                <a class="btn danger" href="./delete_event.php?ctf_id=<?= (int)$e['ctf_id'] ?>" onclick="return confirm('Delete this CTF? This will remove registrations too.');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
