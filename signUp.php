<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");


    $account=new Account($connection);
    //hash password always same
    // echo hash("sha512","password");
   
    //once form submit button clicked
    if(isset($_POST["submitButton"])){
        echo "<script>
        console.log('Sign up button clicked ✅');
        </script>";
      

        // Call the static function from FromSanitizer.php
        $firstName=FormSanitizer::sanitizeFormString($_POST["firstName"]);
        $lastName=FormSanitizer::sanitizeFormString($_POST["lastName"]);

        $username=FormSanitizer::sanitizeForm_username($_POST["username"]);

        $email=FormSanitizer::sanitizeFormEmail($_POST["email"]);
        $email2=FormSanitizer::sanitizeFormEmail($_POST["email2"]);

        $password=FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $password2=FormSanitizer::sanitizeFormPassword($_POST["password2"]);

        echo "<script>
        console.log('After Sanitizer =$firstName/$lastName/$username/$email/$email2/$password/$password2  ✅');
        </script>";


        //fn call
        // fn return true or flase
        $wasSuccessful= $account->register($firstName,$lastName,$username,$email,$email2,$password,$password2);
        
        if($wasSuccessful){

            //SUCCSS
            //******REDIRECT TO INDEX.PHP********
            //session variable-->its an array
            $_SESSION["userLoggedIn"] = $username;
            header("Location: index.php");
            //goto config.php to -->session_start()
            }
        else {
            echo "Registration failed ❗";
        }

    }

    //remember and echo the inputted value-->
    function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }

    }




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>YouTube - Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet"  href="assets/css/styles.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    

    
</head>
<body>
    <div class="signMainContainer">
        <div class="signInContainer">

            <div class="column">

                <div class="header">
                    <img src="./assets/images/VideoTubeLogo.png" alt="Site logo" title="Site logo">
                    <h3>Sign Up</h3>
                    <span>to continue YouTube</span>
                </div>

                <div class="loginForm">
                    <form action="signUp.php" method="POST">
                        <!-- error message show here -->
                        <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                        <input type="text" name="firstName" value="<?php getInputValue('firstName'); ?>" placeholder="First name" required>

                        <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                        <input type="text" name="lastName" value="<?php getInputValue('lastName'); ?>" placeholder="Last name" required>

                        <?php echo $account->getError(Constants::$username_Characters); ?>
                        <?php echo $account->getError(Constants::$username_Taken); ?>
                        <input type="text" name="username" value="<?php getInputValue('username'); ?>" placeholder="Username" required>

                        <?php echo $account->getError(Constants::$emailInvalid); ?>
                        <?php echo $account->getError(Constants::$emailTaken); ?>
                        <input type="email" name="email" value="<?php getInputValue('email'); ?>" placeholder="Email" required>

                        <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
                        <input type="email" name="email2" value="<?php getInputValue('email2'); ?>" placeholder="Confirm email" required>

                        <?php echo $account->getError(Constants::$passwordNotAlphaNumeric); ?>
                        <?php echo $account->getError(Constants::$passwordLength); ?>
                        <input type="password" name="password" value="<?php getInputValue('password'); ?>" placeholder="Password" required>

                        <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
                        <input type="password" name="password2" value="<?php getInputValue('password2'); ?>" placeholder="Confirm password" required>

                        <input type='submit'  value='Submit' name='submitButton'>
                    </form>
                </div>
                
                <a href="signIn.php">
                    <p class="signInMesssage">Already have an account? Sign in here</p>
                </a>

            </div>

        </div>
        <div class="cinematic-container">
            <div class="cinematic-text">
                <h2>Welcome to YouTube</h2>
                <p>Experience the best videos on the web.</p>
                <!-- Add more cinematic text as needed -->
            </div>
        </div>
    </div>
</body>
</html>
