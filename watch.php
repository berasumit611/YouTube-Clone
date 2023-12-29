<?php 
    require_once "includes/header.php";
    
    require_once "includes/classes/VideoPlayer.php"; 
    require_once "includes/classes/VideoInfoSection.php"; 
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
<!-- ---------------------------------------------------- -->



<div class="watchLeftColumn">
    <?php
        $videoPlayer=new VideoPlayer($video);
        echo $videoPlayer->create(true);

        $videoInfoSection=new VideoInfoSection($video,$connection,$userLoggedInObj);
        echo $videoInfoSection->create();

        
    ?>


</div>
<div class="suggestions">
    VIDEO SUGGESTIONS
</div>


                
<?php 
require_once "includes/footer.php"; 
?>