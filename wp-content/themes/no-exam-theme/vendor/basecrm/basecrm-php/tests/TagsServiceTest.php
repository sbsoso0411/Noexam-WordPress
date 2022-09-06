<?php
namespace BaseCRM;

class TagsServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tags, 'all'));
  }
  
  public function testCreateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tags, 'create'));
  }
  
  public function testGetMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tags, 'get'));
  }
  
  public function testUpdateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tags, 'update'));
  }
  
  public function testDestroyMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tags, 'destroy'));
  }

  public function testAll()
  {
    $tags = self::$client->tags->all(['page' => 1]);
    $this->assertInternalType('array', $tags);
 
  }

  public function testCreate()
  {
    $this->assertInternalType('array', self::$tag);
    $this->assertGreaterThanOrEqual(1, count(self::$tag));
 
  }

  public function testGet()
  {
    $foundTag = self::$client->tags->get(self::$tag['id']);
    $this->assertInternalType('array', $foundTag);
    $this->assertEquals($foundTag['id'], self::$tag['id']);
 
  }

  public function testUpdate()
  {
    $updatedTag = self::$client->tags->update(self::$tag['id'], self::$tag);
    $this->assertInternalType('array', $updatedTag);
    $this->assertEquals($updatedTag['id'], self::$tag['id']);
 
  }

  public function testDestroy()
  {
    $newTag = self::createTag();
    $this->assertTrue(self::$client->tags->destroy($newTag['id']));
 
  }
}  
