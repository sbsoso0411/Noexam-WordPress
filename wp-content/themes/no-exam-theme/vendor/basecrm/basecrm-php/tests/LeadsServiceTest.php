<?php
namespace BaseCRM;

class LeadsServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->leads, 'all'));
  }
  
  public function testCreateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->leads, 'create'));
  }
  
  public function testGetMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->leads, 'get'));
  }
  
  public function testUpdateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->leads, 'update'));
  }
  
  public function testDestroyMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->leads, 'destroy'));
  }

  public function testAll()
  {
    $leads = self::$client->leads->all(['page' => 1]);
    $this->assertInternalType('array', $leads);
 
  }

  public function testCreate()
  {
    $this->assertInternalType('array', self::$lead);
    $this->assertGreaterThanOrEqual(1, count(self::$lead));
 
  }

  public function testGet()
  {
    $foundLead = self::$client->leads->get(self::$lead['id']);
    $this->assertInternalType('array', $foundLead);
    $this->assertEquals($foundLead['id'], self::$lead['id']);
 
  }

  public function testUpdate()
  {
    $updatedLead = self::$client->leads->update(self::$lead['id'], self::$lead);
    $this->assertInternalType('array', $updatedLead);
    $this->assertEquals($updatedLead['id'], self::$lead['id']);
 
  }

  public function testDestroy()
  {
    $newLead = self::createLead();
    $this->assertTrue(self::$client->leads->destroy($newLead['id']));
 
  }
}  
