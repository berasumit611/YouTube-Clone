<?php

class VideoGrid{
    private $conn,$userLoggedInObj;
    private $largeMode=false;
    private $gridClass="videoGrid";


    public function __construct($conn,$userLoggedInObj){
        $this->conn=$conn;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function create($videos, $title, $showFilter){

        if($videos==null){
            //case no video pass in
            $gridItems=$this->generateItems();
        }else{
            //in case video pass 
            $gridItems=$this->generateItemsFromVideos($videos);

        }

        //in some page title is shown
        $header="";
        if($title != null){
            $header=$this->createGridHeader($title,$showFilter);
        }

        return 
        "   $header
            <div class='$this->gridClass'>       
                $gridItems
            </div>
        ";

    }
    public function generateItems(){
        //random 15 videos show
        $query=$this->conn->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
        $query->execute();

        $elementHtml="";
        while($row=$query->fetch(PDO::FETCH_ASSOC)){
            $videoObj=new Video($this->conn,$row,$this->userLoggedInObj);

            $item=new VideoGridItem($videoObj,$this->largeMode);

            $elementHtml.=$item->create();
        }

        return $elementHtml;
    }
    public function generateItemsFromVideos($videos){

    }
    public function createGridHeader($title,$showFilter){

    }

    
}


?>