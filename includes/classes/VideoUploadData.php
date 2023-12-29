<?php
    class VideoUploadData {
        //as of now make it public..better with private and get method
        
        public $videoDataArray,$title,$description,$privacy,$category,$uplodedBy;


        public function __construct($videoDataArray,$title,$description,$privacy,$category,$uplodedBy){
            $this->videoDataArray=$videoDataArray;
            $this->title=$title;
            $this->description=$description;
            $this->privacy=$privacy;
            $this->category=$category;
            $this->uploadedBy=$uplodedBy;
        }


    }


?>