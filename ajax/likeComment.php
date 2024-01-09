<?php
//very similar to likeVideo.php 
require_once("../includes/config.php");
require_once("../includes/classes/Comment.php");
require_once("../includes/classes/User.php");



    $usernameLoggedIn=$_SESSION["userLoggedIn"];
    $videoId = $_POST["videoId"];
    $commentId=$_POST["commentId"];   

    //all like related fuctionality write in video class
    $userLoggedInObj=new User($connection,$usernameLoggedIn);
    $commentObj=new Comment($connection,$commentId,$userLoggedInObj,$videoId);

    //now call the like logic fn
    echo $commentObj->like();
    

?>