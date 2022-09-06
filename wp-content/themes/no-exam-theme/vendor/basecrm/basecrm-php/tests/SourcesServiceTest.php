<?php
namespace BaseCRM;

class SourcesServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->sources, 'all'));
  }
  
  public function testCreateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->sources, 'create'));
  }
  
  public function testGetMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->sources, 'get'));
  }
  
  public function testUpdateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->sources, 'update'));
  }
  
  public function testDestroyMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->sources, 'destroy'));
  }

  public function testAll()
  {
    $sources = self::$client->sources->all(['page' => 1]);
    $this->assertInternalType('array', $sources);
 
  }

  public function testCreate()
  {
    $this->assertInternalType('array', self::$source);
    $this->assertGreaterThanOrEqual(1, count(self::$source));
 
  }

  public function testGet()
  {
    $foundSource = self::$client->sources->get(self::$source['id']);
    $this->assertInternalType('array', $foundSource);
    $this->assertEquals($foundSource['id'], self::$source['id']);
 
  }

  public function testUpdate()
  {
    $updatedSource = self::$client->sources->update(self::$source['id'], self::$source);
    $this->assertInternalType('array', $updatedSource);
    $this->assertEquals($updatedSource['id'], self::$source['id']);
 
  }

  public function testDestroy()
  {
    $newSource = self::createSource();
    $this->assertTrue(self::$client->sources->destroy($newSource['id']));
 
  }
}  
