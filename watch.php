<?php 
    require_once "includes/header.php";
    
    require_once "includes/classes/VideoPlayer.php"; 
    require_once "includes/classes/VideoInfoSection.php"; 
    require_once "includes/classes/CommentSection.php"; 
    

    // session_destroy(); testing purpose 

    //get take data from url
    if(!isset($_GET["id"])){
        echo "no url passed on to the page";
        exit();
    }
    //video object
    $video = new Video($connection,$_GET["id"],$userLoggedInObj);
    // echo $video->get_videoTitle();
    $video->incrementViews();
    // echo $video->get_videoViews();

?>

<!-- we only need this js on this page so we include here -->
<script src="assets/js/videoPlayerActions.js"></script>

<!-- postComment function -->
<script src="assets/js/commentAction.js"></script>

<!-- ---------------------------------------------------- -->



<div class="watchLeftColumn">
    <?php
    // video player
        $videoPlayer=new VideoPlayer($video);
        echo $videoPlayer->create(true);
    // video info
        $videoInfoSection=new VideoInfoSection($video,$connection,$userLoggedInObj);
        echo $videoInfoSection->create();

    //1> comment info-->same as videoinfosection
        $commentSection=new CommentSection($video,$connection,$userLoggedInObj);
        echo $commentSection->create();


        
    ?>


</div>
<div class="suggestions">
    VIDEO SUGGESTIONS
</div>


                
<?php 
require_once "includes/footer.php"; 
?>