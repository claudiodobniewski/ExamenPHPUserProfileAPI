<?php
namespace IntrawayBundle\Tools\file\upload;

use IntrawayBundle\Tools\validation\PatternValidation;

class LoadFile{
    
    /**
    * Image source from...
    * @var String
    */
    private $url = null;
    
    /**
     * Local folder to upload image
     * @var String
     * @return \Tools\file\upload\LoadFile
     */
    private $uploadFolder = null;
    
    /**
     * Local filename of uploaded file
     * @var String
     * @return \Tools\file\upload\LoadFile
     */
    private $fileName = false;
    
    /**
     * Error message, String value only if have an error, else false.
     * Only give the last error message.
     * 
     * @var String
     */
    private $err = false;
    
    function __construct(){
    
    }
    
    public function setUrl( $url){
        
        if( PatternValidation::validateUrlPattern($url)){
            $this->url = $url;
        }
        
        return $this;
    }
    
    /**
     * 
     * @return String
     */
    public function getUrl(){
        return $this->url;
    }
    
    /**
     * 
     * @param String $uploadFolder
     * @return 
     */
    public function setUploadForlder( $uploadFolder){

        if( $uploadFolder){
            $this->uploadFolder = $uploadFolder;
        }
    
        return $this;
    }
    
    /**
     *
     * @return String
     */
    public function getUploadFoler(){
        return $this->uploadFolder;
    }
    
    /**
     * 
     * @param String $filename
     * @return \Tools\file\upload\LoadFile
     */
    public function setFilename( $filename){
    
        $this->fileName = $filename;
    
        return $this;
    }
    
    /**
     *
     * @return String
     */
    public function getFilename(){
        return $this->fileName;
    }
    
    /**
     * 
     * @param String $err
     * @return \Tools\file\upload\LoadFile
     */
    protected function setErr( $err){
    
        $this->err = $err;
    
        return $this;
    }
    
    /**
     *
     * @return String || false
     */
    public function getErr(){
        return $this->err;
    }
    
    /**
     *  TRUE if any error occurs,false otherwise
     * @return boolean
     */
    public function isErr(){
        return !$this->err === false;
    }
    
    /**
     * Reset error status
     */
    public function resetErr(){
        $this->err = false;
    }
    
    /**
     * @return \Tools\file\upload\LoadFile
     */
    public function loadFile(){
        
        if(!$this->isErr() && !is_null($this->url) && !is_null($this->uploadFolder) ){
            
            if(!$this->getFilename())
                $this->setFilename( md5(uniqid()).'.'.pathinfo($this->getUrl(), PATHINFO_EXTENSION) );
            else{
                $this->setFilename($this->getFilename().'.'.pathinfo($this->getUrl(), PATHINFO_EXTENSION));
            }
                
            $upload_folder_ready = is_dir($this->getUploadFoler());
            //echo $this->getUploadFoler();exit();
            if(!$upload_folder_ready){
                $upload_folder_ready = mkdir($this->getUploadFoler(),0755, true);
            }
                
            
            if(
                $upload_folder_ready &&
                is_dir($this->getUploadFoler()) &&
                is_writable($this->getUploadFoler()) ){
                if(!file_put_contents($this->getFullpath(), file_get_contents($this->getUrl()))){
                    $this->setErr('FAILED UPLOAD FILE FROM ['.$this->getUrl().']');
                }
                if(!file_exists($this->getFullpath()) ){
                    $this->setErr('POST UPLOAD CHECK: NEW FILE NOT FOUND ['.$this->$this->getFullpath().']');
                }
            }else{
                $this->setErr('UPLOAD FOLDER VALIDATION ERROR ['.$this->getUploadFoler().']');
            }
            
        }
        
        return $this;
    }
    
    public function getFullpath(){
        return $this->getUploadFoler().DIRECTORY_SEPARATOR.$this->getFilename();
    }
}