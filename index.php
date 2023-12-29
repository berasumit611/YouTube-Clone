<?php 
require_once "includes/header.php"; 
?>

                
                <?php
                
                    // if(isset($_SESSION["userLoggedIn"])){
                    //     echo "USER IS LOGGED IN " . $_SESSION["userLoggedIn"];
                    // }else{
                    //     echo "***** NOT LOGGED IN ******";
                    // }
                    echo "user logged in: " .$userLoggedInObj->get_username();
                    echo "full name: " .$userLoggedInObj->get_fullName();
                ?>

                
<?php 
require_once "includes/footer.php"; 
?>