<?php

require_once("../includes/config.php");
require_once("../includes/classes/User.php");
include("../includes/classes/Comment.php");



//get value
$postedBy=$_POST['postedBy'];
$commentText=$_POST['commentText'];
$videoId=$_POST['videoId'];
$responseTo=isset($_POST['responseTo']) ? $_POST['responseTo'] : 0;

    if(isset($commentText) && isset($postedBy) && isset($videoId)){
        $userLoggedInObj=new User($connection,$_SESSION["userLoggedIn"]);

        // echo $commentText."--".$postedBy."--".$videoId;

        //write query to insert data

        $query=$connection->prepare("INSERT INTO comments 
        (postedBy, videoId, responseTo, body)
        VALUES (:postedBy, :videoId, :responseTo, :body)");
        $query->bindParam(":postedBy",$postedBy);
        $query->bindParam(":videoId",$videoId);
        $query->bindParam(":responseTo",$responseTo);
        $query->bindParam(":body",$commentText);

        $query->execute();

        //retrive new comment comment.php-->
        //comment class instance

        $newComment=new Comment($connection,$connection->lastInsertId(),$userLoggedInObj,$videoId );

        //return html
        echo $newComment->create();

    }else{
        //error handling
        echo "One or more parameters not passed into subscribe.php file";
    }



?>