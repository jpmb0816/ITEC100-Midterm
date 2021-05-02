<?php
// Website Title
include_once('website_config.php');

// Page Title
$pageTitle = 'Register';

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
$username = $password = $confirm_password = $email = '';
$username_err = $password_err = $confirm_password_err = $email_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);

    // Validate username
    if (empty($username)) {
        $username_err = 'Please enter a username.';
    } else {
        // Prepare a select statement
        $stmt = $link->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");

        if (
            $stmt &&
            $stmt->bind_param('s', $username) &&
            $stmt->execute() &&
            $stmt->store_result() &&
            $stmt->num_rows() == 1
        ) {
            $username_err = 'Username already exist.';
        }

        $stmt->close();
    }

    // Validate password
    if (empty($password)) {
        $password_err = 'Please enter a password.';
    } else if (strlen($password) < 8) {
        $password_err = 'Password must be 8 characters and above.';
    } else {
        $containLowercase = preg_match('/[a-z]/', $password);
        $containUppercase = preg_match('/[A-Z]/', $password);
        $containDigit = preg_match('/\d/', $password);
        $containSpecialCharacter = preg_match('/[^a-zA-Z\d]/', $password);

        if (!$containLowercase) {
            $password_err = 'Password must contain lowercase.';
        } else if (!$containUppercase) {
            $password_err = 'Password must contain uppercase.';
        } else if (!$containDigit) {
            $password_err = 'Password must contain number.';
        } else if (!$containSpecialCharacter) {
            $password_err = 'Password must contain special character.';
        }
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $confirm_password_err = 'Please confirm password.';
    } else {
        if (empty($password_err) && ($password == $confirm_password)) {
            $password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
        } else {
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Validate email
    if (empty($email)) {
        $email_err = 'Please enter email.';
    } else {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Prepare a select statement
            $stmt = $link->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");

            if (
                $stmt &&
                $stmt->bind_param('s', $email) &&
                $stmt->execute() &&
                $stmt->store_result() &&
                $stmt->num_rows() == 1
            ) {
                $email_err = 'Email already exist.';
            }

            $stmt->close();
        } else {
            $email_err = 'Invalid email format.';
        }
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {

        // Prepare an insert statement
        $stmt = $link->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");

        if (
            $stmt &&
            $stmt->bind_param('sss', $username, $password, $email) &&
            $stmt->execute()
        ) {
            // Redirect to login page
            header('location: index.php');
        } else {
            header('location: error.php');
            exit;
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
        <div class="card mt-5 mx-auto" style="width: 320px;">
            <div class="card-body">
                <!-- Card title -->
                <h5 class="card-title">Sign Up</h5>
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
                    <!-- Confirm Password -->
                    <label for="confirm_password" class="sr-only">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control <?php if (!empty($confirm_password_err)) {
                                                                                                                    echo 'is-invalid';
                                                                                                                } ?> mt-3" placeholder="Confirm Password">
                    <div class="invalid-feedback">
                        <?php echo $confirm_password_err; ?>
                    </div>
                    <!-- Email -->
                    <label for="email" class="sr-only">Email</label>
                    <input type="text" id="email" name="email" class="form-control <?php if (!empty($email_err)) {
                                                                                        echo 'is-invalid';
                                                                                    } ?> mt-3" value="<?php echo $email; ?>" placeholder="Email">
                    <div class="invalid-feedback">
                        <?php echo $email_err; ?>
                    </div>
                    <p class="mt-3">Already have an account? <a href="index.php">Login here</a>.</p>
                    <!-- Sign in button -->
                    <div class="mt-3">
                        <button class="btn btn-primary btn-block">Sign Up</button>
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