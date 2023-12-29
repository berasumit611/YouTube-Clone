<?php
class User{

    private $conn,$sqlData;

    public function __construct($connection,$usernameLoggedIn){
        $this->conn=$connection;

        // retrive all data for logged in user
        $query=$this->conn->prepare("SELECT * FROM users WHERE username=:un");
        $query->bindParam(":un",$usernameLoggedIn);
        $query->execute();

        //store fetch data its an array
        $this->sqlData=$query->fetch(PDO::FETCH_ASSOC);

        // $this->printRetriveData();
        
    }
    // public function printRetriveData(){
    //     echo '<pre>';
    //     print_r($this->sqlData);
    //     echo '</pre>';
    // }

    public function get_username(){
        return $this->sqlData["username"];
    }
    public function get_fullName(){
        return $this->sqlData["firstName"] . " " . $this->sqlData["lastName"];
    }
    public function get_firstName(){
        return $this->sqlData["firstName"];
    }
    public function get_lastName(){
        return $this->sqlData["lastName"];
    }
    public function get_email(){
        return $this->sqlData["email"];
    }
    public function get_profilePic(){
        return $this->sqlData["profilePic"];
    }
    public function get_signUpDate(){
        return $this->sqlData["signUpDate"];
    }

    //checking user logged in or not
    public static function isLoggedIn(){
        return isset($_SESSION["userLoggedIn"]);
    }

    // function handle subscriber

    public function isSubscriberTo($userTo){
        $query=$this->conn->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
        $query->bindParam(":userTo",$userTo);
        $query->bindParam(":userFrom",$username);

        $username=$this->get_username();

        $query->execute();

        return $query->rowCount()>0;

    }
    public function get_SubscriberCount($userTo){
        $query=$this->conn->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
        $query->bindParam(":userTo",$userTo);
       
        $query->execute();

        return $query->rowCount();

    }

}

?>