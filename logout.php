<?php
// Website config
include_once('website_config.php');

// Initialize the session
session_start();

// if the user is already logged in and authenticated
if ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) && (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true)) {
    require_once('database_config.php');

    $id = $_SESSION['id'];

    // Prepare a select statement
    $stmt = $link->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, 'logout')");

    if (
        $stmt &&
        $stmt->bind_param('i', $id) &&
        $stmt->execute()
    ) {
    }

    $stmt->close();
    $link->close();
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('location: index.php');
