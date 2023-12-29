

<?php
 class VideoDetailsFormProvider{
    private $conn;
    //constructor to pass connection variable from config file
    public function __construct($connection){
        $this->conn=$connection;
    }


    public function createUploadForm(){
        $fileInput=$this->createFileInput();
        $titleInput=$this->createTitleInput();
        $descriptionInput=$this->createDescriptionInput();
        $privacyInput=$this->createPrivacyInput();
        $categoriesInput=$this->createCategoriesInput();
        $uploadButton=$this->createUploadButton();
        return 
        " <form action='processing.php' method='POST' enctype='multipart/form-data'>
            $fileInput
            $titleInput
            $descriptionInput
            $privacyInput
            $categoriesInput
            $uploadButton
        </form>
           ";
    }

    private function createFileInput(){
        return
        "<div class='mb-3'>
            <input class='form-control' type='file' id='formFile' required name='fileInput'>
        </div>";
    }

    private function createTitleInput(){
        return "
        <div class='input-group mb-3'>
            <span class='input-group-text' id='inputGroup-sizing-default'>Title</span>
            <input type='text' class='form-control' aria-label='Sizing example input' aria-describedby='inputGroup-sizing-default' placeholder='Video title ...' name='titleInput'>
        </div>";
    }

    private function createDescriptionInput(){
        return "<div class='form-floating mb-3'>
                    <textarea class='form-control'  id='floatingTextarea2' style='height: 100px' name='descriptionInput'></textarea>
                    <label for='floatingTextarea2'>Video description</label>
                </div>";
    }

    private function createPrivacyInput(){
        return 
        "<div class='input-group mb-3'>
        <label class='input-group-text' for='inputGroupSelect01'>Privacy</label>
        <select class='form-select' name='privacyInput' id='inputGroupSelect01'>
          <option value='0'>Private</option>
          <option value='1'>Public</option>
        </select>
      </div>
      ";
    }

    private function createCategoriesInput(){
        //after db connection in config file write query to retrive data
        $query=$this->conn->prepare("SELECT * FROM categories");
        $query->execute();

        $html="<div class='input-group mb-3'>
        <label class='input-group-text' for='inputGroupSelect01'>Categories</label>
        <select class='form-select' name='categoryInput' id='inputGroupSelect01'>
         ";
        
             while($row=$query->fetch(PDO::FETCH_ASSOC)){
                $name=$row["name"];
                $id=$row["id"];
                
                $html.= "<option value='$id'>$name</option>";
            }
        
            $html.="</select>
            </div>";
        

            return $html;

    }
    private function createUploadButton(){
        return "<input class='btn btn-primary' type='submit'  value='Upload' name='uploadButton'>";
    }
 }
?>