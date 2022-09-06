<?php
namespace BaseCRM;

class LossReasonsServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->lossReasons, 'all'));
  }
  
  public function testCreateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->lossReasons, 'create'));
  }
  
  public function testGetMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->lossReasons, 'get'));
  }
  
  public function testUpdateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->lossReasons, 'update'));
  }
  
  public function testDestroyMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->lossReasons, 'destroy'));
  }

  public function testAll()
  {
    $lossReasons = self::$client->lossReasons->all(['page' => 1]);
    $this->assertInternalType('array', $lossReasons);
 
  }

  public function testCreate()
  {
    $this->assertInternalType('array', self::$lossReason);
    $this->assertGreaterThanOrEqual(1, count(self::$lossReason));
 
  }

  public function testGet()
  {
    $foundLossReason = self::$client->lossReasons->get(self::$lossReason['id']);
    $this->assertInternalType('array', $foundLossReason);
    $this->assertEquals($foundLossReason['id'], self::$lossReason['id']);
 
  }

  public function testUpdate()
  {
    $updatedLossReason = self::$client->lossReasons->update(self::$lossReason['id'], self::$lossReason);
    $this->assertInternalType('array', $updatedLossReason);
    $this->assertEquals($updatedLossReason['id'], self::$lossReason['id']);
 
  }

  public function testDestroy()
  {
    $newLossReason = self::createLossReason();
    $this->assertTrue(self::$client->lossReasons->destroy($newLossReason['id']));
 
  }
}  
