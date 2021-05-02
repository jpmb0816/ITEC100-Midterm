<?php
// Website Title
include_once('website_config.php');

// Page Title
$pageTitle = 'Forgot Password';

// Initialize the session
session_start();

if ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) && (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true)) {
    // Check if the user is already logged in and authenticated, if yes then redirect user to home page
    header('location: home.php');
    exit;
} else if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Check if the user is already logged in and not authenticated, if yes then redirect user to enter authentication code page
    header('location: enter_authentication_code.php');
    exit;
} else if (isset($_SESSION['forgot_password']) && $_SESSION['forgot_password'] === true) {
    // Check if the user is already in forgot password process, if yes then redirect user to reset password page
    header('location: reset_password.php');
    exit;
}

// Include database config file
require_once 'database_config.php';

// Define variables and initialize with empty values
$email = $email_err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter email.';
    } else {
        $email = trim($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = 'Invalid email format.';
        }
    }

    if (empty($email_err)) {
        // Prepare a select statement
        $stmt = $link->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");

        if (
            $stmt &&
            $stmt->bind_param('s', $email) &&
            $stmt->execute() &&
            $stmt->store_result() &&
            $stmt->bind_result($id) &&
            $stmt->fetch()
        ) {
            $_SESSION['forgot_password'] = true;
            $_SESSION['forgot_password_id'] = $id;

            $stmt = $link->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, 'forgot password')");

            if (
                $stmt &&
                $stmt->bind_param('i', $id) &&
                $stmt->execute()
            ) {
            }

            // Redirect user to reset password webpage
            header('location: reset_password.php');
        } else {
            // Display an error message if email doesn't exist
            $email_err = 'Email does not exist.';
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
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
                <h5 class="card-title"><?php echo $pageTitle; ?></h5>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <!-- Email -->
                    <input type="email" id="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?> mt-3" placeholder="Email">
                    <div class="invalid-feedback">
                        <?php echo $email_err; ?>
                    </div>
                    <!-- Find button -->
                    <div class="mt-3">
                        <button class="btn btn-primary btn-block">Find</button>
                    </div>
                </form>
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