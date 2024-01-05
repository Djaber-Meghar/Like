<?php
// like.php

session_start(); // Start the session

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'likes';

$db = new mysqli($host, $username, $password, $database);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


// Update the like count
$db->query("UPDATE likes SET count = count + 1 WHERE 1");




// Get the updated like count
$result = $db->query("SELECT count FROM likes WHERE 1");
$row = $result->fetch_assoc();
$likeCount = $row['count'];

$db->close();

echo json_encode(['success' => true, 'likeCount' => $likeCount]);
?>
