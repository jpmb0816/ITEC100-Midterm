<?php
// Website Title
include_once('website_config.php');

// Page Title
$pageTitle = 'Login';

// Initialize the session
session_start();

if ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) && (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true)) {
    // Check if the user is already logged in and authenticated, if yes then redirect him to home page
    header('location: home.php');
    exit;
} elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Check if the user is already logged in and not authenticated, if yes then redirect him to enter authentication code page
    header('location: enter_authentication_code.php');
    exit;
}

// Include database config file
require_once 'database_config.php';

// Define variables and initialize with empty values
$username = $password = '';
$username_err = $password_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if username is empty
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter a username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Check if password is empty
    if (empty($_POST['password'])) {
        $password_err = 'Please enter a password.';
    } else {
        $password = $_POST['password'];
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $stmt = $link->prepare("SELECT id, username, password FROM users WHERE username = ?");

        if (
            $stmt &&
            $stmt->bind_param('s', $username) &&
            $stmt->execute() &&
            $stmt->store_result() &&
            $stmt->bind_result($id, $username, $hashed_password) &&
            $stmt->fetch()
        ) {
            if (password_verify($password, $hashed_password)) {
                // Store data in session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['authenticated'] = false;

                $id = $_SESSION['id'];
                $code = rand(100000, 1000000);
                $dateTime = new DateTime();
                $dateTimeFormat = 'Y-m-d H:i:s';
                $created_at = $dateTime->format($dateTimeFormat);
                $dateTime->add(new DateInterval('PT5M'));
                $expiration = $dateTime->format($dateTimeFormat);

                $stmt = $link->prepare("INSERT INTO authentication_code(user_id, code, created_at, expiration) VALUES (?, ?, ?, ?)");

                if (
                    $stmt &&
                    $stmt->bind_param('iiss', $id, $code, $created_at, $expiration) &&
                    $stmt->execute()
                ) {
                    // Redirect user to enter authentication code page
                    header('location: enter_authentication_code.php');
                }
            } else {
                // Display an error message if password is not valid
                $password_err = 'Wrong Password.';
            }
        } else {
            // Display an error message if username doesn't exist
            $username_err = 'User does not exist.';
        }

        $stmt->close();
    }

    $link->close();
}
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
        <div class="card mt-5 mx-auto" style="width: 350px;">
            <div class="card-body">
                <!-- Card title -->
                <h5 class="card-title">Sign In</h5>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <!-- Username -->
                    <label for="username" class="sr-only">Username</label>
                    <input type="text" id="username" name="username" class="form-control <?php if (!empty($username_err)) {
                                                                                                echo 'is-invalid';
                                                                                            } ?> mt-3" value="<?php echo $username; ?>" placeholder="Username">
                    <div class="invalid-feedback">
                        <?php echo $username_err; ?>
                    </div>
                    <!-- Password -->
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" id="password" name="password" class="form-control <?php if (!empty($password_err)) {
                                                                                                    echo 'is-invalid';
                                                                                                } ?> mt-3" placeholder="Password">
                    <div class="invalid-feedback">
                        <?php echo $password_err; ?>
                    </div>
                    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
                    <p class="mt-3">Forgot password? <a href="forgot_password.php">Reset here</a>.</p>
                    <!-- Sign in button -->
                    <div class="mt-3">
                        <button class="btn btn-primary btn-block">Sign In</button>
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