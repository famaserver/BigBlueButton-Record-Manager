<?php

// Database configuration - update these values for your environment
$databaseHost = 'localhost';
$databaseName = 'bigbluebutton_db';
$databaseUser = 'root';
$databasePass = '';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Create a singleton-like PDO instance
function getPDOConnection() {
	static $pdo = null;
	if ($pdo instanceof PDO) {
		return $pdo;
	}

	global $databaseHost, $databaseName, $databaseUser, $databasePass;
	$dsn = "mysql:host={$databaseHost};dbname={$databaseName};charset=utf8mb4";
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	];

	try {
		$pdo = new PDO($dsn, $databaseUser, $databasePass, $options);
	} catch (PDOException $e) {
		http_response_code(500);
		die('Database connection failed.');
	}

	return $pdo;
}

?>
