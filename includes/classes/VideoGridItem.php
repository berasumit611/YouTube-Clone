<?php
class VideoGridItem{

    private $video,$largeMode;

    public function __construct($video,$largeMode){
        $this->video=$video;
        $this->largeMode=$largeMode;

    }
    public function create(){
        //creating individual video item
        $thumbnail=$this->createThumbnail();
        $details=$this->createDetails();
        $url="watch.php?id=". $this->video->get_videoId();

        return 
                "
                <a href='$url'>
                    <div class='videoGridItem'>
                        $thumbnail
                        $details
                    </div>
                </a>
                ";
    }
    public function createThumbnail(){
        return "test";
    }
    public function createDetails(){
        return "";
    }















}

?>