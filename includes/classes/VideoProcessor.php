<?php

    class VideoProcessor{
        private $conn;
        //50 megabytes=52,428,800 bytes
        private $sizeLimit = 52428800;
        private $allowedVideoType=array( "mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");

        private $ffmpegPath ="C:/ffmpeg/bin/ffmpeg.exe";
        private $ffprobePath ="C:/ffmpeg/bin/ffprobe.exe";

        // CONSTRUCTOR
        public function __construct($connection){
            $this->conn=$connection;
        }

        // MAIN FUNCTION HANDLE ALL UPLOAD RELATED WORK
        public function upload($videoUploadData){

            $targetDir="uploades/videos/";
            $videoData=$videoUploadData->videoDataArray;
            
         
            //-->uploades/videos/4545424/dogs playing.avi
           $tempFilePath = $targetDir . uniqid() . basename($videoData['fileInput']['name']);


            //-->uploades/videos/4545424/dogs_playing.avi
            $tempFilePath=str_replace(" ", "_", $tempFilePath);
            // echo $tempFilePath;


            $isValidData=$this->processData($videoData,$tempFilePath);
            if(!$isValidData){
                return false;
            }

            
            
            // if all data are valid then we move the uploded file from temp directory to final directory video folder
            if(move_uploaded_file($videoData['fileInput']['tmp_name'],$tempFilePath)){
                echo "<script>alert('File moved succesfully ✅...');</script>";


                $finalFilePath=$targetDir . uniqid() . ".mp4";
                // echo $finalFilePath;

                if(!$this->insertVideoData($videoUploadData,$finalFilePath)){
                    echo "<script>alert('Insert query failed ❗...');</script>";
                    return false;
                }

                if(!$this->convertVideoToMp4($tempFilePath,$finalFilePath)){
                    echo "<script>alert('File format not converted ❌...');</script>";
                    return false;
                }
                else echo "<script>alert('File format sucessfully converted ✅...');</script>";

                if($this->deleteOldFile($tempFilePath)){
                    echo "<script>alert('Old file deleted ✅...');</script>";
                }else return false;

                if(!$this->generateThumbnails($finalFilePath)){
                    echo "<script>alert('COULD NOT GENERATE THUMBNAILS...❌');</script>";
                    return false;
                }

                return true;
            }

            
            

           

        }


        // ALL PRIVATE FUNCTIONS
        private function processData($videoData,$tempFilePath){
            //EXTRACT FILE EXTENSION
            $videoType=pathinfo($tempFilePath,PATHINFO_EXTENSION);

            //
            if(!$this->isValidSize($videoData)){
                echo "File size too large ❗. Can't be more than " . $this->sizeLimit ." bytes";
            }
            else if(!$this->isValidType($videoType)){
                echo "Invalid file type ❗.";
                return false;
            }
            else if($this->hasError($videoData)){
                echo "Error code ❗ :" . $videoData['fileInput']['error'];
                return false;
            }

            //for valid all data return true
            return true;
        }
        private function isValidSize($videoData){
            return $videoData['fileInput']['size']<=$this->sizeLimit;
        }
        private function isValidType($videoType){
            $lowerCase=strtolower($videoType);
            return in_array($lowerCase,$this->allowedVideoType);
        }
        private function hasError($videoData){
            //if has error return true
            return $videoData['fileInput']['error'] !=0;
        }
        private function insertVideoData($videoUploadData,$finalFilePath){

           $query=$this->conn->prepare("INSERT INTO videos (uploadedBy,title,description,privacy,filePath,category) 
           VALUES(:uploadedBy,:title,:description,:privacy,:filePath,:category)");

            //...    :filePath. These placeholders will be bound to actual values in the next steps.
            $query->bindParam(":title",$videoUploadData->title);
            $query->bindParam(":uploadedBy",$videoUploadData->uploadedBy);$query->bindParam(":description",$videoUploadData->description);$query->bindParam(":privacy",$videoUploadData->privacy);$query->bindParam(":filePath",$finalFilePath);$query->bindParam(":category",$videoUploadData->category);

            //if execute return true
            return    $query->execute();
       

        }
        private function convertVideoToMp4($tempFilePath,$finalFilePath){
            //FFmpeg command
            $cmd = "{$this->ffmpegPath} -i $tempFilePath -c:v libx264 -c:a aac $finalFilePath 2>&1";

            //output of cmd
            $outputLog= array();
            exec($cmd,$outputLog,$returnCode);

            //if there was an error $returnCode != 0
            if($returnCode !=0){
                //command fail
                foreach($outputLog as $line){
                    echo $line . "<br>";
                }
                return false;
            }
            //for successful conversion return true
            return true;
        }
        private function deleteOldFile($tempFilePath){
            //The unlink() function deletes a file
            if(!unlink($tempFilePath)){
                echo "<script>alert('Could not delete old file ❗...');</script>";
                return false;
            }
            return true;
        }

        private function generateThumbnails($finalFilePath){
            $thumbnailSize="210x118";
            $numOfThumbnails=3;
            $pathToThumbnail= "C:/xampp/htdocs/YouTube/uploades/videos/thumbnails/";

            $duration=$this->getVideoDuration($finalFilePath);

            //video id id video table ID
            //lastInsertId()--->inbuild fn to get last id
            $queryToGetVideoId=$this->conn->lastInsertId();
            $videoId=$queryToGetVideoId;

            //fn call
            $this->updateDuration($duration,$videoId);

            // echo "Durattion : " . $duration;

            for($num=1;$num<=$numOfThumbnails;$num++){
                $imageName=uniqid() . ".jpg";
                //just logic
                $interval = ($duration*0.8)/$numOfThumbnails*$num;
                $fullThumbnailPath="$pathToThumbnail/$videoId-$imageName";

                $cmd = "{$this->ffmpegPath} -i $finalFilePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath  2>&1";

                //output of cmd & error check
                $outputLog= array();
                exec($cmd,$outputLog,$returnCode);
                if($returnCode !=0){
                    foreach($outputLog as $line){
                        echo $line . "<br>";
                    }

                }

                $queryForInsertThumbnail=$this->conn->prepare("INSERT INTO thumbnails(videoId,filePath,selected)
                        VALUES(:videoId,:filePath,:selected)");
                $queryForInsertThumbnail->bindParam(":videoId",$videoId);
                $queryForInsertThumbnail->bindParam(":filePath",$fullThumbnailPath);
                $queryForInsertThumbnail->bindParam(":selected",$selected);

                //bydefault first thumbnail selected
                $selected= $num ==1?1:0;

                $sucess=$queryForInsertThumbnail->execute();
                if(!$sucess){
                    echo "error inserting thumbnail...❌";
                    return false;
                }
            }

            return true;

        }
        private function getVideoDuration($finalFilePath){

            $ffprobeCommand = "{$this->ffprobePath} -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $finalFilePath";
            return (int)shell_exec($ffprobeCommand);
        }
        private function updateDuration($duration,$videoId){
            
            $hours=floor($duration/3600); 
            $mins=floor(($duration - ($hours*3600))/60);
            $secs=floor($duration%60);

            if($hours<1){
                $hours="";
            }else $hours =$hours . ":";
            $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
            $secs = ($secs < 10) ? "0" . $secs : $secs;

            $duration=$hours.$mins.$secs;

            $query=$this->conn->prepare("UPDATE videos SET duration=:duration WHERE id=:videoId");
            $query->bindParam(":duration",$duration);
            $query->bindParam(":videoId",$videoId);
            
            $query->execute();

        }


    }



?>