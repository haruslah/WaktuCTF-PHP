<?php
require("./conf.php");

// Array of all CREATE TABLE statements
$tables = [];

// USERS table
$tables[] = "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ctf_joined INT DEFAULT 0
)";

// CTF_EVENTS table
$tables[] = "CREATE TABLE IF NOT EXISTS ctf_events (
    ctf_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    format VARCHAR(50) NOT NULL,
    description TEXT DEFAULT NULL
)";

// USER_CTF table
$tables[] = "CREATE TABLE IF NOT EXISTS user_ctf (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    ctf_id INT NOT NULL,
    team_name VARCHAR(100) DEFAULT NULL,
    experience ENUM('beginner','intermediate','advanced') DEFAULT 'beginner',
    notify VARCHAR(255) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_ctf (user_id, ctf_id)
)";

// TEAMS table
$tables[] = "CREATE TABLE IF NOT EXISTS teams (
    team_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) NOT NULL UNIQUE,
    created_by INT UNSIGNED NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

// TEAM_MEMBERS table
$tables[] = "CREATE TABLE IF NOT EXISTS team_members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    team_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_team_user (team_id, user_id)
)";

// Execute all table creations
foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
