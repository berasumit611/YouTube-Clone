<?php
require_once("ButtonProvider.php");
require_once("CommentControls.php");

//from comment.php to showing newly comment to display

class Comment{

    private $conn,$sqlData,$userLoggedInObj,$videoId;
    
    public function __construct($conn, $input, $userLoggedInObj, $videoId){

        // echo "ID:".$input;
        //if input is last row id of comments
        if(!is_array($input)){
            $query = $conn->prepare("SELECT * FROM comments where id = :id");
            $query->bindParam(":id",$input);
            $query->execute();

            //retrive data
            $input = $query->fetch(PDO::FETCH_ASSOC);
        }

        //set parameter
        $this->sqlData = $input;
        $this->conn = $conn;
        $this->userLoggedInObj = $userLoggedInObj;
        $this->videoId = $videoId;

        
     }

     public function create(){
        //retrive data
        $commentId=$this->sqlData["id"];
        $postedBy=$this->sqlData["postedBy"];
        $videoId=$this->sqlData["videoId"];
        $body=$this->sqlData["body"];
        // $responseTo=$this->sqlData["responseTo"];

        $profileButton=ButtonProvider::createProfileButton($this->conn,$postedBy);

        //get timespan
        $timeSpan="x";

        //creating instances of CommentControls class object
        /*----------note-->$this-> refers to whole comment class object--------*/
        $commentControlsObj=new CommentControls($this->conn,$this,$this->userLoggedInObj);
        $retriveCommentControls=$commentControlsObj->create();

        return 
        "
        <div class='itemContainer'>
            <div class='comment'>
                $profileButton
            </div>
            <div class='mainContainer'>

                <div class='commentHeader'>
                    <a href='profile.php?username=$postedBy'>
                        <span class='username'>$postedBy</span>
                    <a>
                    <span class='timestamp'>$timeSpan</span>
                </div>

                <div class='body'>
                    $body
                </div>

            </div>
            $retriveCommentControls
        </div>
        ";


     }
     public function get_commentLikes(){
        $commentId= $this->get_commentId();

        //get no of comment likes from likes table
        $query=$this->conn->prepare("SELECT count(*) as 'count' FROM likes WHERE commentId=:commentId");
        $query->bindParam(":commentId",$commentId);
        $query->execute();

        $data=$query->fetch(PDO::FETCH_ASSOC);
        $numOfLikes=$data["count"];

         //get no of comment dislikes from dislikes table
         $query=$this->conn->prepare("SELECT count(*) as 'count' FROM dislikes WHERE commentId=:commentId");
         $query->bindParam(":commentId",$commentId);
         $query->execute();

        $data=$query->fetch(PDO::FETCH_ASSOC);
        $numOfdisLikes=$data["count"];

        return $numOfLikes - $numOfdisLikes;
     }

     public function get_commentId(){
        return $this->sqlData["id"];
     }
     public function get_videoId(){
        return  $this->videoId ;
     }

     /*----------similar like video--------*/
     public function was_liked(){
        $commentId=$this->get_commentId();
        $username=$this->userLoggedInObj->get_username();


        //checking looged in user already liked the comment id or not?

        $query=$this->conn->prepare("SELECT * FROM likes WHERE username=:username AND commentId=:commentId");

        $query->bindParam(":username",$username);
        $query->bindParam(":commentId",$commentId);


        $query->execute();

        //if alredy liked its true
        return $query->rowCount() > 0;

     }
     public function was_DisLiked(){
        $commentId=$this->get_commentId();
        $username=$this->userLoggedInObj->get_username();

        $query=$this->conn->prepare("SELECT * FROM dislikes WHERE username=:username AND commentId=:commentId");

        $query->bindParam(":username",$username);
        $query->bindParam(":commentId",$commentId);


        $query->execute();

        //if alredy disliked its true
        return $query->rowCount() > 0;
     }
}

?>