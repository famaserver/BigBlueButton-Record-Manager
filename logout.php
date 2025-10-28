<?php
require_once __DIR__ . '/auth.php';

// Clear session
session_unset();
session_destroy();

// Redirect to login
header('Location: login.php');
exit;

