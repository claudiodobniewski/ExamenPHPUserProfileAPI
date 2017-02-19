<?php
// src/IntrawayBundle/Controller/UserControllerTest.php
namespace test\IntrawayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetUserFixture1()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/userProfile/1');
 //   {"id":1,"name":"Jorge Perez","email":"jorge.perez73@hotmail.com","Image":null}
        $expectedResponse = array('id' => 1,'name' => 'Jorge Perez','email' => 'jorge.perez73@hotmail.com','Image' => null);
        $currentResponse = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expectedResponse, $currentResponse);
    }
    
    public function testGetUserNotExist()
    {
        $client = static::createClient();
    
        $crawler = $client->request('GET', '/userProfile/999999999');
        
        //   {"message":"NOT FOUND USER [Id:548b50a158a8d785a9beb] [UserId:9999]"}
        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $currentResponse = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertTrue(array_key_exists('message', $currentResponse));
        $this->assertTrue(strpos($currentResponse['message'], 'NOT FOUND USER') !== false);
    }
}
