<?php

class ButtonProvider{
    public static $signInFunction="notSignedIn()";

    public static function createLink($link){
        return User::isLoggedIn() ?$link: ButtonProvider::$signInFunction;
    }
    
    //generic btn ctrate fn
    public static function createButton($text,$imgSrc,$action,$class){
        $img=($imgSrc==null)?"":"<img src='$imgSrc'>";
        
        //change action if needed if notsign in
        $action = ButtonProvider::createLink($action);

        return 
        "<button 
            type='button' 
            onclick='$action'  
            class='btn btn-light $class'>
                $img
                <span class='text'>$text</span>
        </button>";
    }

    // button link subscribe/edit kind of link
    public static function createHyperLinkButton($text,$imgSrc,$href,$class){
        $img=($imgSrc==null)?"":"<img src='$imgSrc'>";


        return 
        "<a href='$href'>
            <button 
                type='button' 
                class='btn btn-light $class'>
                    $img
                    <span class='text'>$text</span>
            </button>
        </a>"
        ;
    }


    //videoinfoSection-->$profilebutton
    public static function createProfileButton($conn,$username){
        //user class obj
        $userObj=new User($conn,$username);
        //fn call
        $profilePic=$userObj->get_profilePic();
        $link = "profile.php?username=$username";

        //when we click profile image go to profile page
        return 
        "
        <a href='$link'>
            <img src='$profilePic' class='profilePicture'>
        </a>
        ";

    }

    public static function createEditVideoButton($videoId){
        // echo $videoId;
        $href="editVideo.php?videoId=$videoId";
        //calling createButton fn 

        $button=ButtonProvider::createHyperLinkButton("EDIT VIDEO",null,$href,"edit button");
   
        return 
        "<div class='editVideoButtonContainer'>
            $button
        </div>
        ";

    }
    public static function createSubscriberButton($conn,$userToObj,$userLoggedInObj){
        $userTo=$userToObj->get_username(); //whose video is watching
        $userLoggedIn=$userLoggedInObj->get_username(); //who watching video
        // echo $userTo. "----" .$userLoggedIn;

        $isSubscriberTo= $userLoggedInObj->isSubscriberTo($userTo);
        $buttonText= $isSubscriberTo ? "SUBSCRIBED" :"SUBSCRIBE";
        // echo $buttonText;

        $buttonText.= " " . $userToObj->get_SubscriberCount($userTo);

        $buttonClass= $isSubscriberTo ? "unsubscribe button" : "subscribe button";

        $action="subscribe(\"$userTo\",\"$userLoggedIn\",this)";//js

        $button=ButtonProvider::createButton($buttonText,null,$action,$buttonClass);

        return "
            <div class='subscribeButtonContainer'>
                $button
            </div>
        ";


    }
}
?>