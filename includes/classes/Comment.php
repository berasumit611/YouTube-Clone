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
        //retrive data------>
        $commentId=$this->sqlData["id"];
        $postedBy=$this->sqlData["postedBy"];
        $videoId=$this->sqlData["videoId"];
        $body=$this->sqlData["body"];
        $responseTo=$this->sqlData["responseTo"];
        $datePosted=$this->sqlData["datePosted"];



        $profileButton=ButtonProvider::createProfileButton($this->conn,$postedBy);

        //get timespan
        $timeSpan=$this->time_elapsed_string($datePosted);

        //creating instances of CommentControls class object
        /*----------note-->$this-> refers to whole comment class object--------*/
        $commentControlsObj=new CommentControls($this->conn,$this,$this->userLoggedInObj);
        $retriveCommentControls=$commentControlsObj->create();

        $numResponses=$this->getNumberOfReplies();
        $viewRepliesText="";//if no reply by default no text

        if($numResponses > 0){
            $viewRepliesText="<span class='repliesSection viewReplies onclick='getReplies($commentId,this,$videoId)'>View all $numResponses replies</span>";
        }else{
            $viewRepliesText="<div class='repliesSection'></div>";
        }



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
            $viewRepliesText
        </div>
        ";


     }
     /*----------function-------*/

     public function getNumberOfReplies(){
        $commentId=$this->get_commentId();
        $query=$this->conn->prepare("SELECT count(*) as 'count' FROM comments WHERE responseTo=:commentId");
        $query->bindParam(":commentId",$commentId);
        $query->execute();

        $data=$query->fetch(PDO::FETCH_ASSOC);
        $numOfReplies=$data["count"];

        return $numOfReplies;
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

     // generic function-->https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
      public function time_elapsed_string($datetime, $full = false) 
      {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);

            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }

            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }
// --------------------comment like dislike functionality same as vedio---------

        public function like(){

            $commentId=$this->get_commentId();
            $username=$this->userLoggedInObj->get_username();
    
            if($this->was_liked()){

                $query= $this->conn->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
                $query->bindParam(":username",$username);
                $query->bindParam(":commentId",$commentId);
    
                $query->execute();
    
               
                return -1;
    
            }else{
                $query= $this->conn->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
                $query->bindParam(":username",$username);
                $query->bindParam(":commentId",$commentId);
    
                $query->execute();
    
                $count=$query->rowCount();

                $query=$this->conn->prepare("INSERT INTO likes(username,commentId) VALUES (:username,:commentId)");
                $query->bindParam(":username",$username);
                $query->bindParam(":commentId",$commentId);
    
                $query->execute();
    

                return 1+$count;
    
                
            }
    
        }
    
        public function disLike(){
            $commentId=$this->get_commentId();
            $username=$this->userLoggedInObj->get_username();
            
            if($this->was_DisLiked()){
                
                $query= $this->conn->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
                $query->bindParam(":username",$username);
                $query->bindParam(":commentId",$commentId);
                $query->execute();
                
                return 1;
                
            }else{
                $query= $this->conn->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
                $query->bindParam(":username",$username);
                $query->bindParam(":commentId",$commentId);
                
                $query->execute();
                
                $count=$query->rowCount();
                
                $query=$this->conn->prepare("INSERT INTO dislikes(username,commentId) VALUES (:username,:commentId)");
                $query->bindParam(":username",$username);
                $query->bindParam(":commentId",$commentId);
                
                $query->execute();
                

                return -1+$count;
                
                
            }
            
        }









    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    }




?>