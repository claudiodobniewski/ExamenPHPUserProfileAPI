<?php
// test/IntrawayBundle/validation/PatternValidationt.php
namespace test\IntrawayBundle\validation;

use IntrawayBundle\Tools\validation\PatternValidation;

class PatternValidationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * check OK simple sintax
     */
    public function testValidateEmail01()
    {
        $email = 'claudiojd@gmail.com';
        $validation = PatternValidation::validateEmailPattern($email);
        
        $this->assertTrue($validation);
    }
    
    /**
     * check NOK double "at"
     */
    public function testValidateEmail02()
    {
        $email = 'claudiojd@gmail.com@google.com';
        $validation = PatternValidation::validateEmailPattern($email);
    
        $this->assertNotTrue($validation);
    }
    
    /**
     * check NOK inner space
     */
    public function testValidateEmail03()
    {
        $email = 'claudio dobniewskigmail.com';
        $validation = PatternValidation::validateEmailPattern($email);
    
        $this->assertNotTrue($validation);
    }
    
    /**
     * check OK simple sintax whith underscore
     */
    public function testValidateEmail04()
    {
        $email = 'claudio_dobniewski@gmail.com';
        $validation = PatternValidation::validateEmailPattern($email);
    
        $this->assertTrue($validation);
    }
    
    /**
     * check NOK simple sintax whith undersocre on name and domain
     */
    public function testValidateEmail05()
    {
        $email = 'claudio_dobniewski@gmail_com';
        $validation = PatternValidation::validateEmailPattern($email);
    
        $this->assertNotTrue($validation);
    }
    
    /**
     * check OK simple sintax camelcase
     */
    public function testValidateEmail06()
    {
        $email = 'claudioDobniewski@gmail_com';
        $validation = PatternValidation::validateEmailPattern($email);
    
        $this->assertNotTrue($validation);
    }
    
    /**
     * check OK simple sintax
     */
    public function testValidateUrll01()
    {
        $url = 'http://www.google.com';
        $validation = PatternValidation::validateUrlPattern($url);
    
        $this->assertTrue($validation);
    }
    
    /**
     * check NOK backslash
     */
    public function testValidateUrll02()
    {
        $url = 'http://www.google.com\other\path';
        $validation = PatternValidation::validateUrlPattern($url);
    
        $this->assertNotTrue($validation);
    }
    
    /**
     * check NOK whitout protocol
     */
    public function testValidateUrll03()
    {
        $url = 'www.google.com';
        $validation = PatternValidation::validateUrlPattern($url);
    
        $this->assertNotTrue($validation);
    }
    
    /**
     * check NOK only protocol
     */
    public function testValidateUrll04()
    {
        $url = 'http://';
        $validation = PatternValidation::validateUrlPattern($url);
    
        $this->assertNotTrue($validation);
    }
    
    /**
     * check OK simple sintax https
     */
    public function testValidateUrll05()
    {
        $url = 'https://www.google.com';
        $validation = PatternValidation::validateUrlPattern($url);
    
        $this->assertTrue($validation);
    }
    
    /**
     * check OK simple sintax https
     */
    public function testValidateUrll06()
    {
        $url = 'ssh://www.google.com';
        $validation = PatternValidation::validateUrlPattern($url);
    
        $this->assertTrue($validation);
    }
}
