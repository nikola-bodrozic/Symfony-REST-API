<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase {

    private $tuser = ["name" => "phpunit-mike2", "role" => "manager"];
    private $uid;
    private $usedid = array();

    protected function setUp() {
        $client = static::createClient();
        $client->request(
                'POST', 
                '/user', 
                array(), 
                array(), 
                array(
                    'CONTENT_TYPE' => 'application/json', 
                    "HTTP_AUTHORIZATION" => "Bearer e4d3"
                ), 
                json_encode($this->tuser)
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        //print_r($data);die;
        $this->uid = $data['id'];
        array_push($this->usedid, $data['id']);
    }

    protected function tearDown() {
        if ( !empty($this->usedid) ){
            $client = static::createClient();
            $client->request(
                    'DELETE', 
                    '/user/' . $this->uid,
                    array(), 
                    array(), 
                    array(
                        'CONTENT_TYPE' => 'application/json', 
                        "HTTP_AUTHORIZATION" => "Bearer e4d3"
                    )
            );            
        }
    }

    public function testGetUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user', [], [], []);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent(), true);
        $arr_index = count($data)-1;
        //error_log("---" . __METHOD__ . " : " . __LINE__  . "\n" . print_r($data, TRUE), 0);        
        $this->assertEquals($data[$arr_index]['name'], $this->tuser['name']);
        $this->assertEquals($data[$arr_index]['role'], $this->tuser['role']);        
    }
    
    public function testIdAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/' . $this->uid);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($data['name'], $this->tuser['name']);
        $this->assertEquals($data['role'], $this->tuser['role']);
        //error_log("---" . __METHOD__ . " : " . __LINE__  . "\n" . print_r($data, TRUE), 0);  
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/1345345345345');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteAction() {
        $client = static::createClient();
        $client->request(
                'DELETE', 
                '/user/' . $this->uid, 
                array(), 
                array(), 
                array(
                    'CONTENT_TYPE' => 'application/json', 
                    "HTTP_AUTHORIZATION" => "Bearer e4d3"
                )
        );
        array_pop($this->usedid);
        $this->assertEquals(200, $client->getResponse()->getStatusCode()); 
    }
    
    function testUpdateAction() {
        $client = static::createClient();
        $client->request(
                'PUT', 
                '/user/' . $this->uid , 
                array(), 
                array(), 
                array(
                    'CONTENT_TYPE' => 'application/json', 
                    "HTTP_AUTHORIZATION" => "Bearer e4d3"
                ),
                json_encode(["name" => "phpunit-test-update", "role" => "manager"])
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode()); 
               
    }
}
