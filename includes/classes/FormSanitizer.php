<?php
 class FormSanitizer{

    public static function sanitizeFormString($inputText){
        // Remove HTML tags to prevent XSS attacks
        $inputText=strip_tags($inputText);
        // Remove spaces
        $inputText=str_replace(" ","",$inputText);
        // Convert to lowercase
        $inputText=strtolower($inputText);
        // Capitalize the first letter
        $inputText=ucfirst($inputText);


        return $inputText;
    }

    public static function sanitizeForm_username($inputText){
        // Remove HTML tags to prevent XSS attacks
        $inputText=strip_tags($inputText);
        // Remove spaces
        $inputText=str_replace(" ","",$inputText);


        return $inputText;
    }

    public static function sanitizeFormPassword($inputText){
        // Remove HTML tags to prevent XSS attacks
        $inputText=strip_tags($inputText);


        return $inputText;
    }

    public static function sanitizeFormEmail($inputText){
        // Remove HTML tags to prevent XSS attacks
        $inputText=strip_tags($inputText);
        // Remove spaces
        $inputText=str_replace(" ","",$inputText);
        


        return $inputText;
    }


 }


?>