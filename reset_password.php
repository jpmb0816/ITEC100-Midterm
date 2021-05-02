<?php
// Website Title
include_once('website_config.php');

// Page Title
$pageTitle = 'Reset Password';

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
} else if (!isset($_SESSION['forgot_password']) && $_SESSION['forgot_password'] === false) {
    // Check if the user is not in forgot password process, if yes then redirect user to forgot password page
    header('location: forgot_password.php');
    exit;
}

// Include database config file
require_once 'database_config.php';

// Define variables and initialize with empty values
$password = $confirm_password = '';
$password_err = $confirm_password_err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate password
    if (empty($_POST['password'])) {
        $password_err = 'Please enter a password.';
    } else if (strlen($_POST['password']) < 8) {
        $password_err = 'Password must be 8 characters and above.';
    } else {
        $password = $_POST['password'];

        $containLowercase = preg_match('/[a-z]/', $password);
        $containUppercase = preg_match('/[A-Z]/', $password);
        $containDigit = preg_match('/\d/', $password);
        $containSpecialCharacter = preg_match('/[^a-zA-Z\d]/', $password);

        if (!$containLowercase) {
            $password_err = 'Password must contain lowercase.';
        } elseif (!$containUppercase) {
            $password_err = 'Password must contain uppercase.';
        } elseif (!$containDigit) {
            $password_err = 'Password must contain number.';
        } elseif (!$containSpecialCharacter) {
            $password_err = 'Password must contain special character.';
        }
    }

    // Validate confirm password
    if (empty($_POST['confirm_password'])) {
        $confirm_password_err = 'Please confirm password.';
    } else {
        $confirm_password = $_POST['confirm_password'];

        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Check input errors before updating and inserting in database
    if (empty($password_err) && empty($confirm_password_err)) {
        $id = $_SESSION['forgot_password_id'];
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare a select statement
        $stmt = $link->prepare("UPDATE users SET password = ? WHERE id = ?");

        if (
            $stmt &&
            $stmt->bind_param('si', $password, $id) &&
            $stmt->execute()
        ) {
            // Prepare a select statement
            $stmt = $link->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, 'reset password')");

            if (
                $stmt &&
                $stmt->bind_param('i', $id) &&
                $stmt->execute()
            ) {
            }

            unset($_SESSION['forgot_password']);
            unset($_SESSION['forgot_password_id']);

            // Redirect to login page
            header('location: index.php');
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
                    <!-- Password -->
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" id="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?> mt-3" placeholder="Password">
                    <div class="invalid-feedback">
                        <?php echo $password_err; ?>
                    </div>
                    <!-- Confirm Password -->
                    <label for="confirm_password" class="sr-only">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?> mt-3" placeholder="Confirm Password">
                    <div class="invalid-feedback">
                        <?php echo $confirm_password_err; ?>
                    </div>
                    <!-- Confirm button -->
                    <div class="mt-3">
                        <button class="btn btn-primary btn-block">Confirm</button>
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