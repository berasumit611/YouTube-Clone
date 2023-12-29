<?php
require_once("../includes/config.php");
require_once("../includes/classes/Video.php");
require_once("../includes/classes/User.php");


    $usernameLoggedIn=$_SESSION["userLoggedIn"];
    $videoId = $_POST["videoId"];


    $userLoggedInObj=new User($connection,$usernameLoggedIn);
    $video=new Video($connection,$videoId,$userLoggedInObj);

    echo $video->disLike();

?>