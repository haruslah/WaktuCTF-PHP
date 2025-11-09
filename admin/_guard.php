<?php
require("../conf.php");
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user'];
$check = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$check->bind_param("i", $userId);
$check->execute();
$res = $check->get_result();
$row = $res->fetch_assoc();

if (!$row || (int)$row['is_admin'] !== 1) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}
