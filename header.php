<?php
$headerHomeStatus = '';
$headerRegisterStatus = '';
$headerAboutStatus = '';
$headerActiveStatus = 'active';

if ($pageTitle == 'Login' || $pageTitle == 'Home') {
    $headerHomeStatus = $headerActiveStatus;
} else if ($pageTitle == 'Register') {
    $headerRegisterStatus = $headerActiveStatus;
} else if ($pageTitle == 'About') {
    $headerAboutStatus = $headerActiveStatus;
}
?>

<nav class="navbar navbar-expand-md navbar-light bg-light sticky top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php
        if ($pageTitle != 'Error') {
            echo
            '<div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">';
            // Login
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo
                '<li class="nav-item ' . $headerHomeStatus . '">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>';
            } else {
                echo
                '<li class="nav-item ' . $headerHomeStatus . '">
                        <a class="nav-link" href="index.php">Login</a>
                    </li>';
            }

            // Register
            if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
                echo
                '<li class="nav-item ' . $headerRegisterStatus . '">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>';
            }

            // About
            echo
            '<li class="nav-item ' . $headerAboutStatus . '">
                <a class="nav-link" href="about.php">About</a>
            </li>';

            // Logout
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo
                '<li class="nav-item">
                        <a class="nav-link" href="logout.php">Sign Out</a>
                    </li>';
            }
            echo '
                </ul>
            </div>';
        }
        ?>

    </div>
</nav>

<?php
