<?php
include_once("Comment.php");

class Video{

    private $conn,$sqlData,$userLoggedInObj;

    public function __construct($connection,$input,$userLoggedInObj){
        $this->conn=$connection;
        $this->userLoggedInObj=$userLoggedInObj;

        //$input is actually sqlquery array so
        if(is_array($input)){
            $this->sqlData=$input;
        }
        else{
            //if input not sql query only video id
            $query=$this->conn->prepare("SELECT * FROM videos WHERE id=:id");
            $query->bindParam(":id",$input);
            $query->execute();

            //store fetch data its an array
            $this->sqlData=$query->fetch(PDO::FETCH_ASSOC); 
        }
        
    }
    
    //helpful functions
    public function get_videoId(){
        return $this->sqlData["id"];
    }
    public function get_videoUploadedBy(){
        return $this->sqlData["uploadedBy"];
    }
    public function get_videoTitle(){
        return $this->sqlData["title"];
    }
    public function get_videoDescription(){
        return $this->sqlData["description"];
    }
    public function get_videoPrivacy(){
        return $this->sqlData["privacy"];
    }
    public function get_videoFilePath(){
        return $this->sqlData["filePath"];
    }
    public function get_videoCategory(){
        return $this->sqlData["category"];
    }
    public function get_videoUploadDate(){
        return $this->sqlData["uploadDate"];
    }
    public function get_videoViews(){
        return $this->sqlData["views"];
    }
    public function get_videoDuration(){
        return $this->sqlData["duration"];
    }
    
    


    //every time refresh page views increment
    public function incrementViews(){

        $query=$this->conn->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
        $query->bindParam(":id",$videoId);

        $videoId=$this->get_videoId();

        $query->execute();

        //update old get views value
        $this->sqlData["views"]=$this->sqlData["views"] + 1;

    }

    //call from videoInfoControls
    public function get_videoLikes(){
        $query=$this->conn->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId=:videoId");
        $query->bindParam(":videoId",$videoId);

        
        $videoId=$this->get_videoId();

        $query->execute();

        //store the fetched result
        $data=$query->fetch(PDO::FETCH_ASSOC);

        return $data["count"];

        
    }
    public function get_videoDisLikes(){
        $query=$this->conn->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId=:videoId");
        $query->bindParam(":videoId",$videoId);

        
        $videoId=$this->get_videoId();

        $query->execute();

        //store the fetched result
        $data=$query->fetch(PDO::FETCH_ASSOC);

        return $data["count"];

        
    }


    //likeVideo--->like
    public function like(){
        // return "sandwitch";
        //first check user alredy like the respected 
        $videoId=$this->get_videoId();
        $username=$this->userLoggedInObj->get_username();

        // $username=$this->userLoggedInObj->get_username();

        // $query=$this->conn->prepare("SELECT * FROM likes WHERE username=:username AND videoId=:videoId");

        // $query->bindParam(":username",$username);
        // $query->bindParam(":videoId",$videoId);


        // $query->execute();

        if($this->wasLikedBy()){
            //user already liked
            // echo "Already liked";
            $query= $this->conn->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$videoId);

            $query->execute();

            // --- ---
            $result=array(
                "likes" => -1,
                "dislikes" => 0
            );
            return json_encode($result);

        }else{
            $query= $this->conn->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$videoId);

            $query->execute();

            $count=$query->rowCount();
            //user not liked yet
            // echo "not like yet";
            $query=$this->conn->prepare("INSERT INTO likes(username,videoId) VALUES (:username,:videoId)");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$videoId);

            $query->execute();

            $result=array(
                "likes" => 1,
                "dislikes" => 0 - $count
            );
            return json_encode($result);

            
        }

    }

    public function wasLikedBy(){
        $videoId=$this->get_videoId();
        $username=$this->userLoggedInObj->get_username();

        $query=$this->conn->prepare("SELECT * FROM likes WHERE username=:username AND videoId=:videoId");

        $query->bindParam(":username",$username);
        $query->bindParam(":videoId",$videoId);


        $query->execute();

        //if alredy liked its true
        return $query->rowCount() > 0;
    }

    //likeVideo.php-->disLike()  dislike functionality
    public function disLike(){

        $videoId=$this->get_videoId();
        $username=$this->userLoggedInObj->get_username();

        if($this->wasDisLikedBy()){

            $query= $this->conn->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$videoId);

            $query->execute();

            // --- ---
            $result=array(
                "likes" => 0,
                "dislikes" => -1
            );
            return json_encode($result);

        }else{
            $query= $this->conn->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$videoId);

            $query->execute();

            $count=$query->rowCount();

            $query=$this->conn->prepare("INSERT INTO dislikes(username,videoId) VALUES (:username,:videoId)");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$videoId);

            $query->execute();

            $result=array(
                "likes" => 0-$count,
                "dislikes" => 1
            );
            return json_encode($result);

            
        }

    }
    public function wasDisLikedBy(){
        $videoId=$this->get_videoId();
        $username=$this->userLoggedInObj->get_username();

        $query=$this->conn->prepare("SELECT * FROM dislikes WHERE username=:username AND videoId=:videoId");

        $query->bindParam(":username",$username);
        $query->bindParam(":videoId",$videoId);


        $query->execute();

        //if alredy disliked its true
        return $query->rowCount() > 0;
    }


    //comment part commentSection.php-->
    public function getNumOfComments(){
        $query=$this->conn->prepare("SELECT * FROM comments WHERE videoId=:videoId");
        $query->bindParam(":videoId",$videoId);

        $videoId=$this->get_videoId();
        $query->execute();

        return $query->rowCount();
    }

    // public function get_Comments() {
    //     $id= $this->get_videoId();
            
    //     $query = $this->conn->prepare("SELECT * FROM comments WHERE videoId = :videoId  ORDER BY datePosted DESC");
    //     $query->bindParam(':videoId', $id);   
        
    //     $query->execute();
    
    //     $comments = array();
    
    //     while($row = $query->fetch(PDO::FETCH_ASSOC)){
    //         $comment = new Comment($this->conn, $row, $this->userLoggedInObj, $id);
    //         array_push($comments,$comment);
    //     }
    
    //     return $comments;
    // }
}


?>