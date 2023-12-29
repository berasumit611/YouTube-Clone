<?php
require_once("../includes/config.php");
require_once("../includes/classes/Video.php");
require_once("../includes/classes/User.php");


// echo "hello";
    // echo $_POST["videoId"];

    $usernameLoggedIn=$_SESSION["userLoggedIn"];
    $videoId = $_POST["videoId"];

    //all like related fuctionality write in video class
    $userLoggedInObj=new User($connection,$usernameLoggedIn);
    $video=new Video($connection,$videoId,$userLoggedInObj);

    //now call the like logic fn
    echo $video->like();
    

?>