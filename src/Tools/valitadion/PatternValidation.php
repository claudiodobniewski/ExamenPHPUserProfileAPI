<?php
namespace Tools\validation;

class PatternValidation{
    
    /**
     * Validate si un String corresponde en sintaxis a una URL
     * Check if an Stirng var have lid URL sintax
     * 
     * @param String $url
     * @return boolean
     */
    public static function valdateUrlPattern(String $url){
        $validate = false;
        if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
            $validate = true;
        }
        return $validate;
    }
    
    /**
     * Valida
     * @param String $email
     * @return boolean
     */
    public static function valdateEmailPattern(String $email){
        $validate = false;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $validate = true;
        }
        return $validate;
    }
    
}