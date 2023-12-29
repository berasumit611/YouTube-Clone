<?php
require_once "includes/classes/VideoInfoControls.php"; 

/*----------IMPORTANT NOTE--------*/
// userTo is whose video is watching
//userloggedin is who login and watch video
//GO TO BUTTONPROVIDER PHP
/*----------IMPORTANT NOTE--------*/



class VideoInfoSection{
    private $video,$conn,$userLoggedInObj;

    public function __construct($video,$connection,$userLoggedInObj){
        $this->video=$video;
        $this->userLoggedInObj=$userLoggedInObj;
        $this->conn=$connection;
    }
    //this create fn has two part
    public function create(){
        return $this->createPrimaryInfo() . $this->createSecondaryInfo();
         
    }
    //more about video
    private function createPrimaryInfo(){
        $videoTitle=$this->video->get_videoTitle();
        $videoViews=$this->video->get_videoViews();

        $videoInfoControls=new VideoInfoControls($this->video,$this->userLoggedInObj);
        $controls=$videoInfoControls->create();
        
        return "
            <div class='videoInfo'>
            <h2>$videoTitle</h2>
                <div class='bottomSection'>
                    <span class='viewCount'>Views : $videoViews</span>   
                    $controls
                </div>
            </div>
        ";
    }
    //more about user--->
    private function createSecondaryInfo(){
        $description = $this->video->get_videoDescription();
        $uploadDate=$this->video->get_videoUploadDate();
        $uploadedBy=$this->video->get_videoUploadedBy();//username
        $profileButton=ButtonProvider:: createProfileButton($this->conn,$uploadedBy);

        // formatting upload date
        $dateTime = new DateTime($uploadDate);
        $formattedDateTime=$dateTime->format('j F Y');


        // user cant subscribe own chanel they can edit
        //if logged in user name and upload user name are same show the, edit button instade subscribe button
        if($uploadedBy==$this->userLoggedInObj->get_username()){
            //show edit button buttonprovider->createEditVideoButton
            $actionButton=ButtonProvider::createEditVideoButton($this->video->get_videoId());
        }else{
            $userToObj=new User($this->conn,$uploadedBy);
            //show subsscribe button
            $actionButton=ButtonProvider::createSubscriberButton($this->conn,$userToObj,$this->userLoggedInObj);
        }

        return 
        "<div class='secondaryInfo'>
            <div class='topRow'> 
                $profileButton

                <div class='uploadInfo'>
                    <span class='owner'>
                        <a href='profile.php?username=$uploadedBy'>
                            $uploadedBy
                        </a>
                    </span>
                    <span class='date'>
                            Published date : $formattedDateTime
                    </span>
                </div>
                $actionButton
            </div>

            <div class='descriptionContainer'>
                $description
            </div>
            
        </div>
        ";
    }

}

?>