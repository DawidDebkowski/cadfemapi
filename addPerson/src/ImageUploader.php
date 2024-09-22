<?php

class ImageUploader {
    private $targetDir;
    private $targetFile;
    private $uploadOk;
    private $imageFileType;
    private $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    private $maxFileSize = 5000000; // 500KB
    
    public function __construct($targetDir) {
        $this->targetDir = $targetDir;
        $this->uploadOk = true;
    }
    
    public function removeFile($path) {
        try {
            unlink($path);
            echo $path . " removed";
        } catch(Exception $ex) {
            echo "file not removed: " . $path;
        }
    }

    public function uploadFile($file) {

        $this->targetFile = $this->targetDir . basename($file["name"]);
        $this->imageFileType = strtolower(pathinfo($this->targetFile, PATHINFO_EXTENSION));
        
        $this->checkImageSize($file["size"]);
        $this->checkImageType($file["tmp_name"]);
        $this->checkFileExists();

        if (!file_exists($this->targetDir)) {
            mkdir($this->targetDir, 0777, true);
        }
        try {
            if ($this->uploadOk) {
                if (move_uploaded_file($file["tmp_name"], $this->targetFile)) {
                    echo "The file " . htmlspecialchars(basename($file["name"])) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
        } 
        } catch(Exception $ex) {
            echo $ex;
            exit();
        }
        return $this->targetFile;
    }
    
    private function checkImageType($tmpName) {
        $check = getimagesize($tmpName);
        if ($check === false) {
            echo "File is not an image.";
            $this->uploadOk = false;
        }
    }
    
    private function checkFileExists() {

        if (file_exists($this->targetFile)) {
            echo "Sorry, file already exists.";
            $this->uploadOk = false;
        }
    }
    
    private function checkImageSize($fileSize) {
        if ($fileSize > $this->maxFileSize) {
            echo "Sorry, your file is too large.";
            $this->uploadOk = false;
        }
    }
    
    public function isAllowedFileType() {
        if (!in_array($this->imageFileType, $this->allowedTypes)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $this->uploadOk = false;
        }
    }
}
?>