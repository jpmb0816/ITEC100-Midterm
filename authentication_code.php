<?php
// Website Title
include_once('website_config.php');

// Page Title
$pageTitle = 'Authentication Code';

// Initialize the session
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    // Check if the user is not logged in, if yes then redirect him to login page
    header('location: index.php');
    exit;
} else if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    // Check if the user is authenticated, if yes then redirect him to home page
    header('location: home.php');
    exit;
}

// Include database config file
require_once 'database_config.php';

// Define variables and initialize
$id = $_SESSION['id'];
$authentication_code = 'EXPIRED';

$stmt = $link->prepare("SELECT code FROM authentication_code WHERE user_id = ? AND NOW() >= created_at AND NOW() <= expiration ORDER BY id DESC limit 1");

if (
    $stmt &&
    $stmt->bind_param('i', $id) &&
    $stmt->execute() &&
    $stmt->store_result() &&
    $stmt->bind_result($code) &&
    $stmt->fetch()
) {
    $authentication_code = $code;
}

$stmt->close();
$link->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title -->
    <title><?php echo $pageTitle; ?> | <?php echo $websiteTitle; ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous">
</head>

<body>
    <!-- Header -->
    <?php include_once('header.php'); ?>

    <!-- Sign in -->
    <div class="jumbotron" style="height: 100%;">
        <div class="card mt-5 mx-auto" style="width: 300px;">
            <div class="card-body">
                <!-- Card title -->
                <h5 class="card-title">Authentication Code</h5>
                <div class="container">
                    <div class="row">
                        <div class="col border rounded text-center py-3">
                            <label style="font-size: 50px;"><?php echo $authentication_code; ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once('footer.php'); ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script>
</body>

</html>