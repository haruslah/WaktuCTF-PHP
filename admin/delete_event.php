<?php
require("./_guard.php");

$ctf_id = isset($_GET['ctf_id']) ? (int)$_GET['ctf_id'] : 0;
if ($ctf_id > 0) {
    // If you didn’t add the FK cascade, explicitly delete registrations first:
    // $conn->prepare("DELETE FROM user_ctf WHERE ctf_id = ?")->bind_param("i", $ctf_id)->execute();

    $stmt = $conn->prepare("DELETE FROM ctf_events WHERE ctf_id = ?");
    $stmt->bind_param("i", $ctf_id);
    $stmt->execute();
}
header("Location: ./dashboard.php");
exit;
