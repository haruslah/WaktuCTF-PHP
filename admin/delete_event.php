<?php
require("./_guard.php");

$ctf_id = isset($_GET['ctf_id']) ? (int)$_GET['ctf_id'] : 0;
if ($ctf_id > 0) {

    $stmt = $conn->prepare("DELETE FROM ctf_events WHERE ctf_id = ?");
    $stmt->bind_param("i", $ctf_id);
    $stmt->execute();
}
header("Location: ./dashboard.php");
exit;
