<?php
class VideoPlayer{
    private $video;
    public function __construct($video){
        $this->video=$video;
    }
    public function create($autoplay){
        if($autoplay){
            $autoplay="autoplay";
        }
        else{
            $autoplay="";
        }
        $videoFilePath=$this->video->get_videoFilePath();

        return "
        <video class='videoPlayer' controls $autoplay >
            <source src='$videoFilePath' type='video/mp4'>
            your browser does not support the video tag
        </video>
        ";
        
    }

}

?>