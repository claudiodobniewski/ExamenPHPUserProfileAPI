<?php
namespace IntrawayBundle\Tools;

class ControllerHelper
{
    
    /**
     * @return string - length 13 random hexa chars
     */
    public static function getRandomId()
    {
        return uniqid(dechex(rand()));
    }
}
