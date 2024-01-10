<!-- connect config file -->
<?php
require_once("includes/config.php");
require_once("includes/classes/ButtonProvider.php");
require_once("includes/classes/User.php");
require_once ("includes/classes/Video.php");
require_once ("includes/classes/VideoGrid.php");
require_once ("includes/classes/VideoGridItem.php");



//in youtube if no login then also indexpage visible 
// $_SESSION["userLoggedIn"] = username
// $usernameLoggedIn=isset($_SESSION["userLoggedIn"]) ? $_SESSION["userLoggedIn"] : "";
$usernameLoggedIn=User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";

$userLoggedInObj=new User($connection,$usernameLoggedIn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>YouTube</title>
    <!-- bootstrap v5.3 cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- main css file -->
    <link rel="stylesheet"  href="assets/css/styles.css">

    <!-- jqury cdn -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- bootstrap js cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- js script -->
    <script src="./assets/js/commonAction.js"></script>
    <!-- subscribe functionality in button provider -->
    <script src="./assets/js/userAction.js"></script>

</head>
<body>
    <!-- icon8 cdn -->
    <a  href="https://icons8.com/icon/eofQ1g5BaAx6/menu"></a> 
    <a href="https://icons8.com"></a>
    <div class="pageContainer">

        <div class="mastHeadContainer">
            <button class="navShowHide">
            
                <img width="30" height="30" src="https://img.icons8.com/fluency/48/menu--v3.png" alt="menu--v3"title="Menu"/>
            </button>

            <a href="index.php" class="logoContainer"> 
                <img src="./assets/images/VideoTubeLogo.png" alt="Site logo" title="Site logo">
            </a>

            <div class="searchBarContainer">
                <form action="search.php" mathod="GET">
                    <input type="text" class="searchBar" name="term" placeholder="Search anything ...">
                    <button class="searchButton"><img src="./assets/images/search.png"></button>
                </form>
            </div>

            <div class="rightIcons">
                <a href="upload.php">
                    <img width="48" height="48" src="https://img.icons8.com/fluency-systems-regular/48/upload--v1.png" alt="upload--v1"/>
                </a>
                <a href="#">
                    <img src="./assets/images/default-male.png" alt="">
                </a>
            </div>
        </div>

        <div class="sideNavContainer" style="display:none;">
        </div>

        <div class="mainSectionContainer">
            <div class="mainContentContainer">