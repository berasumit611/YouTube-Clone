<?php
//ACCOUNT CLASS RESPONSIBLE FOR REGISTER AND LOGIN FORM VALIDATION
class Account{
    /*----------PRIVATE DATA--------*/
    private $conn;
    private $errorArray=array();
    /*----------CONSTRUCTOR--------*/
    public function __construct($connection){
        $this->conn=$connection;
    }

    /*----------LOGIN FUNCTION--------*/
    public function login($un,$pw){
        //first hash the password because saved password in db is hashed
        $pw=hash("sha512",$pw);

        $query=$this->conn->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindParam(":un",$un);
        $query->bindParam(":pw",$pw);

        $query->execute();
        //if data present in db then only one row we found so can be login otherwise login fail
        if($query->rowCount()==1){
            return true;
        }else{
            array_push($this->errorArray,Constants::$loginFailed);
            return false;
        }



    }






    /*----------REGISTER FUNCTION--------*/
    
    public function register($fn,$ln,$un,$em,$em2,$pw,$pw2){
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validate_username($un);
        $this->validateEmails($em,$em2);
        $this->validatePassword($pw,$pw2);

        // if errorArray is empty then all input are validate and they are correct
        if(empty($this->emptyArray)){
            // insert the user data to db
            //return true
            return $this->insertUserData($fn,$ln,$un,$em,$pw);
        }else{
            return false;
        }
    }
    //register fn ---> inserUserData
    private function insertUserData($fn,$ln,$un,$em,$pw){
        
        // hashing password //128character
        $pw=hash("sha512",$pw);
        $profilePic="assets/images/default.png";

        $query=$this->conn->prepare("INSERT INTO users (firstName,lastName,username,email,password,profilePic) VALUES(:fn,:ln,:un,:em,:pw,:profilePic)");
        $query->bindParam(":fn",$fn);
        $query->bindParam(":ln",$ln);
        $query->bindParam(":un",$un);
        $query->bindParam(":em",$em);
        $query->bindParam(":pw",$pw);
        $query->bindParam(":profilePic",$profilePic);

            //if exectute return true
            return $query->execute();

    }

    

    /*----------ALL VALIDATION PRIVATE FUNCTION--------*/
    private function validateFirstName($fn){
        if(strlen($fn)>25||strlen($fn)<2){
            //push some message to error array
            array_push($this->errorArray,Constants::$firstNameCharacters);
        }
    }
    private function validateLastName($ln){
        if(strlen($ln)>25||strlen($ln)<2){
            //push some message to error array
            array_push($this->errorArray,Constants::$lastNameCharacters);
        }
    }
    private function validate_username($un){
        if(strlen($un)>25||strlen($un)<5){
            //push some message to error array
            array_push($this->errorArray,Constants::$username_Characters);
            return; //if this validation fail stop
        }

        //no other exsing username in db mach with username; thats why some query written
        $query=$this->conn->prepare("SELECT username FROM users WHERE username=:un");
        $query->bindParam(":un",$un);
        $query->execute();

        //if 0 then username is unique
        if($query->rowCount() != 0){
            // This username already exists
            array_push($this->errorArray,Constants::$username_Taken);
        }
    }
    private function validateEmails($em,$em2){
        if($em!=$em2){
            //push some message to error array
            array_push($this->errorArray,Constants::$emailsDoNotMatch);
            return; //if this validation fail stop
        }
        /*NOTE--> php build in fn filter_var($email, FILTER_VALIDATE_EMAIL) --> Check if the variable $email is a valid email address:*/

        if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$emailInvalid);
            return;
        }
        
        // query to check already email present in db
        $query=$this->conn->prepare("SELECT email FROM users WHERE email=:em");
        $query->bindParam(":em",$em);
        $query->execute();
        if($query->rowCount() != 0){
            array_push($this->errorArray,Constants::$emailTaken);
        }
    }
    private function validatePassword($pw,$pw2){
        if($pw!=$pw2){
            //push some message to error array
            array_push($this->errorArray,Constants::$passwordsDoNotMatch);
            return; //if this validation fail stop
        }

        //check password alphanumeric regex
        if(preg_match("/[^A-Za-z0-9]/",$pw)){
            array_push($this->errorArray,Constants::$passwordNotAlphaNumeric);
            return;
        }
        if(strlen($pw) >30 || strlen($pw) <5){
            array_push($this->errorArray,Constants::$passwordLength);
        }
    }
    
    
    
    /*----------ERROR MESSAGE FUNCTION--------*/
    //return error message
    public function getError($error){
        if(in_array($error,$this->errorArray)){
            return "<span class='errorMessage'>$error</span>";
        }
    }


    
    
}

?>