<?php
//take value from userTo,userFrom from user Action .js
require_once("../includes/config.php");
require_once("../includes/classes/User.php");



//get value
$userTo=$_POST['userTo'];
$userFrom=$_POST['userFrom'];

    if(isset($userTo) && isset($userFrom)){
        // echo "both paramete come";

        //1 check if the user is subbed?
        //2 if subbed then delete the subb 
        //3 if not subbed then subb insert
        //return the number of subb


        $query=$connection->prepare("SELECT * FROM subscribers WHERE userTo= :userTo AND userFrom= :userFrom");
        $query->bindParam(":userTo",$userTo);
        $query->bindParam(":userFrom",$userFrom);
        $query->execute();

        /*----------imp note--------*/
        /*The function returns true if the rowCount of the executed query is greater than 0, indicating that there is at least one subscription matching the provided conditions. Otherwise, it returns false*/


        //task 1
        if($query->rowCount() == 0){
            //not subscribe yet
            //insert task 2
            $queryInsert=$connection->prepare("INSERT INTO subscribers (userTo, userFrom) 
            VALUES (:userTo, :userFrom)");
            $queryInsert->bindParam(":userTo",$userTo);
            $queryInsert->bindParam(":userFrom",$userFrom);

            $queryInsert->execute();

        }else{
            //task 3
            //already subscriber then onclick delete 
            $queryDelete=$connection->prepare("DELETE FROM subscribers WHERE userTo= :userTo AND userFrom= :userFrom");
            $queryDelete->bindParam(":userTo",$userTo);
            $queryDelete->bindParam(":userFrom",$userFrom);
            $queryDelete->execute();

        }

        //return subb count
        $querySubCount=$connection->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
        $querySubCount->bindParam(":userTo",$userTo);
       
        $querySubCount->execute();

        //retuning currnt sub count
        echo $querySubCount->rowCount();



    }else{
        //error handling
        echo "One or more parameters not passed into subscribe.php file";
    }



?>