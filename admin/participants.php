<?php
require("./_guard.php");

$ctf_id = isset($_GET['ctf_id']) ? (int)$_GET['ctf_id'] : 0;
if ($ctf_id <= 0) { echo "Invalid CTF."; exit; }

$event = $conn->prepare("SELECT title, date FROM ctf_events WHERE ctf_id = ?");
$event->bind_param("i", $ctf_id);
$event->execute();
$eventRow = $event->get_result()->fetch_assoc();

$regs = $conn->prepare("
    SELECT u.username, u.email, uc.team_name, uc.experience, uc.notify, uc.notes, uc.joined_at
    FROM user_ctf uc
    JOIN users u ON u.id = uc.user_id
    WHERE uc.ctf_id = ?
    ORDER BY uc.joined_at DESC
");
$regs->bind_param("i", $ctf_id);
$regs->execute();
$participants = $regs->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Participants - WaktuCTF</title>
  <link rel="icon" href="/images/faviconwctf.png" type="image/png">
  <link rel="stylesheet" href="../style.css">
  <style>
    .admin-wrap{max-width:1000px;margin:120px auto 40px;padding:20px;background:#fff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.08);font-family:Inter,system-ui,sans-serif}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:black;color:#fff;text-decoration:none}
    .btn:hover{background:red}
  </style>
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
    <h2 style="font-family:'Bebas Neue',sans-serif;font-size:3rem;margin-bottom:6px">
      Participants — <?= htmlspecialchars($eventRow['title'] ?? 'Unknown') ?>
    </h2>
    <p style="margin-bottom:16px;color:#666">
      Date: <?= isset($eventRow['date']) ? date("d/m/Y", strtotime($eventRow['date'])) : '-' ?>
    </p>

    <table>
      <thead>
        <tr>
          <th>User</th>
          <th>Team</th>
          <th>Experience</th>
          <th>Notify</th>
          <th>Notes</th>
          <th>Joined</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($participants->num_rows): while($p = $participants->fetch_assoc()): ?>
          <tr>
            <td>
              <div><strong><?= htmlspecialchars($p['username']) ?></strong></div>
              <div style="color:#666"><?= htmlspecialchars($p['email']) ?></div>
            </td>
            <td><?= htmlspecialchars($p['team_name']) ?></td>
            <td><?= htmlspecialchars(ucfirst($p['experience'])) ?></td>
            <td><?= htmlspecialchars($p['notify']) ?></td>
            <td><?= nl2br(htmlspecialchars($p['notes'])) ?></td>
            <td><?= date("d/m/Y H:i", strtotime($p['joined_at'])) ?></td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="6">No participants yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div style="margin-top:16px">
      <a class="btn" href="./dashboard.php">Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
