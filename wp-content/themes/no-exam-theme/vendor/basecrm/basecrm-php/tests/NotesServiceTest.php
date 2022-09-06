<?php
namespace BaseCRM;

class NotesServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->notes, 'all'));
  }
  
  public function testCreateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->notes, 'create'));
  }
  
  public function testGetMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->notes, 'get'));
  }
  
  public function testUpdateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->notes, 'update'));
  }
  
  public function testDestroyMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->notes, 'destroy'));
  }

  public function testAll()
  {
    $notes = self::$client->notes->all(['page' => 1]);
    $this->assertInternalType('array', $notes);
 
  }

  public function testCreate()
  {
    $this->assertInternalType('array', self::$note);
    $this->assertGreaterThanOrEqual(1, count(self::$note));
 
  }

  public function testGet()
  {
    $foundNote = self::$client->notes->get(self::$note['id']);
    $this->assertInternalType('array', $foundNote);
    $this->assertEquals($foundNote['id'], self::$note['id']);
 
  }

  public function testUpdate()
  {
    $updatedNote = self::$client->notes->update(self::$note['id'], self::$note);
    $this->assertInternalType('array', $updatedNote);
    $this->assertEquals($updatedNote['id'], self::$note['id']);
 
  }

  public function testDestroy()
  {
    $newNote = self::createNote();
    $this->assertTrue(self::$client->notes->destroy($newNote['id']));
 
  }
}  
