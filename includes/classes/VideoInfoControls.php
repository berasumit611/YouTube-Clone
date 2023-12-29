<?php
require_once "includes/classes/ButtonProvider.php"; 
class VideoInfoControls{
    private $video,$userLoggedInObj;

    public function __construct($video,$userLoggedInObj){
        $this->video=$video;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function create(){

        $likeButton=$this->createLikeButton();
        $disLikeButton=$this->createDisLikeButton();

        return "
        <div class='controls'>
            $likeButton
            $disLikeButton
        </div>
        ";
         
    }
    private function createLikeButton(){
        $text=$this->video->get_videoLikes();
        $videoId=$this->video->get_videoId();
        //its a js onclick function
        $action="likeVideo(this,$videoId)";
        $class="likeButton";
        $imgSrc="assets/images/thumb-up.png";

        //change btn image if liked alredy
        if($this->video->waslikedBy()){
        $imgSrc="assets/images/thumb-up-active.png";

        }

        //calling generic buttoncreate class to crete button 
        return ButtonProvider::createButton($text,$imgSrc,$action,$class);
    }
    private function createDisLikeButton(){
        $text=$this->video->get_videoDisLikes();
        $videoId=$this->video->get_videoId();
        $action="disLikeVideo(this,$videoId)";
        $class="disLikeButton";
        $imgSrc="assets/images/thumb-down.png";

        //change btn image if liked alredy
        if($this->video->wasDisLikedBy()){
            $imgSrc="assets/images/thumb-down-active.png";
            }

        return ButtonProvider::createButton($text,$imgSrc,$action,$class);
    }
}
?>