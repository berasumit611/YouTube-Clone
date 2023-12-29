<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");



$account=new Account($connection);
//once form submit button clicked
if(isset($_POST["submitButton"])){

    $username=FormSanitizer::sanitizeForm_username($_POST["username"]);
    $password=FormSanitizer::sanitizeFormPassword($_POST["password"]);

    //return true/false
    $wasSuccessful=$account->login($username,$password);

    
    if($wasSuccessful){
        //******REDIRECT TO INDEX.PHP********
        $_SESSION["userLoggedIn"] = $username;
        header("Location: index.php");
        }
    else echo "FAILED LOGIN ❗";

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
    <title>YouTube - Sign In</title>
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
                    <h3>Sign In</h3>
                    <span>to continue YouTube</span>
                </div>

                <div class="loginForm">
                    <form action="signIn.php" method="POST">
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                        <input type="text" name="username" value="<?php getInputValue('username'); ?>"placeholder="Username" required>
                        <input type="password" name="password" value="<?php getInputValue('password'); ?>" placeholder="password" required>
                        <input type="submit" name="submitButton" value="Submit">
                    </form>
                </div>

                
                <a href="signUp.php">
                    <p class="signInMesssage">Dont have an account? create here</p>
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
