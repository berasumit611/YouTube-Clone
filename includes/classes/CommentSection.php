<?php



class CommentSection{   
    private $video,$conn,$userLoggedInObj;

    public function __construct($video,$connection,$userLoggedInObj){
        $this->video=$video;
        $this->userLoggedInObj=$userLoggedInObj;
        $this->conn=$connection;
    }
  
    public function create(){
        //create fn
         return $this->createCommentSection();
        // return "<h1>hi</h1>";
    }

    private function createCommentSection(){

        //retrive no of comments
        $numOfComments=$this->video->getNumOfComments();
        // echo $numOfComments;
        $postedBy=$this->userLoggedInObj->get_username();
        $videoId=$this->video->get_videoId();

        $profileButton=ButtonProvider:: createProfileButton($this->conn,$postedBy);

        /*----------comment button--------*/
        $commentButtonAction="postComment(this,\"$postedBy\",$videoId,null,\"comments\")";

        $commentButton=ButtonProvider::createButton("COMMENT",null,$commentButtonAction,"postComment");

        // $comments = $this->video->get_Comments();
        // $commentItems = "";
        // foreach($comments as $comment) {
        //     $commentItems .= $comment->create();
        // } 

        /*----------return html--------*/

        return 
        "<div class='commentSection'>
            <div class='header'>

                <span class='commentCount'> $numOfComments Comments </span>

                <div class='commentForm'>
                    $profileButton
                    <textarea class='commentBodyClass' placeholder='add a public comment'></textarea>
                    $commentButton
                </div>

                <div class = 'comments'>
                            
                </div>
                  
            </div>
        </div>
        ";
    }

    
}

?>