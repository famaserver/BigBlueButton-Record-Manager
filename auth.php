<?php
require_once __DIR__ . '/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}

?>

