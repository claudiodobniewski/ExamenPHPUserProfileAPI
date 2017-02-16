<?php
namespace Tools\file\upload;


class LoadImageFile extends LoadFile{
    
    private $supportImagetypes = ['jpg','gif','png'];
    
    function __construct(){
        
    }
    
    public function setUrl(String $url){
    
        $fileType = pathinfo($this->getUrl(), PATHINFO_EXTENSION);
        if(in_array(strtolower($fileType), $this->supportImagetypes) ){
            parent::setUrl($url);
        }else{
            $this->setErr('Unsuported Image Type, only allowed ['.join(',',$this->supportImagetypes).'] given ['.$fileType.']');
        }
    
        return $this;
    }
}

