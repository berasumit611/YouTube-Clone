<?php 
require_once "includes/header.php";
require_once "includes/classes/VideoUploadData.php";
require_once "includes/classes/VideoProcessor.php";


    if(!isset($_POST["uploadButton"])){
        echo "No file send to page ❗";
        exit();
    }

    // 1) create file upload data
    //$_FILES-->whole files array
    $videoUpoadData = new VideoUploadData(
        $_FILES, 
        $_POST["titleInput"],
        $_POST["descriptionInput"],
        $_POST["privacyInput"],
        $_POST["categoryInput"],
        $userLoggedInObj->get_username()
        // "replace_later" 
    );

  

// 2) Process video data (upload)
$videoProcessor = new VideoProcessor($connection);
$wasSuccessful = $videoProcessor->upload($videoUpoadData);

// 3) Checking wheather uploaded or not
if($wasSuccessful){
echo "<div id='message'>Video uploded successfully ✅✅</div>";
}
else echo "<div id='message'>Upload failed ❌...</div>";






echo "<script>
        setTimeout(function() {
            document.getElementById('message').style.display = 'none';
        }, 5000); // 7000 milliseconds = 7 seconds
      </script>";

?>