<?php
require_once("includes/config.php");
require_once("classes/PreviewProvider.php");
require_once("classes/Entity.php");
require_once("classes/CategoryContainer.php");
require_once("classes/EntityProvider.php");
require_once("classes/ErrorMessage.php");
require_once("classes/SeasonProvider.php");
require_once("classes/Season.php");
require_once("classes/Video.php");
require_once("classes/VideoProvider.php");


if (!isset($_SESSION['userLoggedIn'])){
    header("Location: register.php");
}

$userLoggedIn = $_SESSION['userLoggedIn']; // username

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test site</title>
    <link rel="stylesheet" href="assets\css\style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/a74952dfa4.js" crossorigin="anonymous"></script>
    <script src="assets\js\script.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
<?php
    if (!isset($hideNav)){
        include_once("includes/navBar.php");
    }
?>
       