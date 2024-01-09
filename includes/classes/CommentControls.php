<?php
require_once "ButtonProvider.php"; 

class CommentControls{
    private $conn,$commentObj,$userLoggedInObj;

    public function __construct($conn,$comment,$userLoggedInObj){
        $this->conn=$conn;
        $this->commentObj=$comment;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function create(){
        $replyButton=$this->createReplyButton();
        $likesCount=$this->createLikesCount();
        $likeButton=$this->createLikeButton();
        $disLikeButton=$this->createDisLikeButton();
        $replySection=$this->createReplySection();

        return "
        <div class='controls'>
            $replyButton
            $likesCount
            $likeButton
            $disLikeButton
        </div>
        $replySection

        ";
         
    }
    private function createReplyButton(){
        $text="REPLY";
        $action="toggleReply(this)";  //WRITTEN IN COMMENT ACTION.JS FILE

        return ButtonProvider::createButton($text,null,$action,null);
    }
    private function createLikesCount(){
        $text=$this->commentObj->get_commentLikes();
        
        if($text == 0) $text = "";
        return "<span class='likesCount'>$text</span>";

    }
    private function createLikeButton(){
        $commentId=$this->commentObj->get_commentId();
        $videoId=$this->commentObj->get_videoId();
       
        $action="likeComment($commentId,this,$videoId)";
        $class="likeButton";
        $imgSrc="assets/images/thumb-up.png";

        
        if($this->commentObj->was_liked()){
        $imgSrc="assets/images/thumb-up-active.png";
        }

        //calling generic buttoncreate class to crete button 
        return ButtonProvider::createButton("",$imgSrc,$action,$class);
    }
    private function createDisLikeButton(){
        $commentId=$this->commentObj->get_commentId();
        $videoId=$this->commentObj->get_videoId();
       
        $action="disLikeComment($commentId,this,$videoId)";
        $class="disLikeButton";
        $imgSrc="assets/images/thumb-down.png";

        //change btn image if liked alredy
        if($this->commentObj->was_DisLiked()){
            $imgSrc="assets/images/thumb-down-active.png";
            }

        return ButtonProvider::createButton("",$imgSrc,$action,$class);
    }

    private function createReplySection(){
        
        $postedBy=$this->userLoggedInObj->get_username();
        $videoId=$this->commentObj->get_videoId();
        $commentId=$this->commentObj->get_commentId();

        $profileButton=ButtonProvider:: createProfileButton($this->conn,$postedBy);

        // $commentButtonAction="postComment(this,\"$postedBy\",$videoId,null,\"comments\")";
        
        $cancelButtonAction="toggleReply(this)";
        $cancelButton=ButtonProvider::createButton("CANCEL",null,$cancelButtonAction,"cancelComment");

        $postButtonAction="toggleReply(this,\"$postedBy\",$videoId,$commentId,\"repliesSection\")";
        $postButton=ButtonProvider::createButton("POST",null,$postButtonAction,"postComment");
        /*----------return html--------*/

        return 
        "  <div class='commentForm hidden' >
                $profileButton
                <textarea class='commentBodyClass' placeholder='reply to comment'></textarea>
                $cancelButton
                $postButton
            </div>
        ";
    }
}
?>