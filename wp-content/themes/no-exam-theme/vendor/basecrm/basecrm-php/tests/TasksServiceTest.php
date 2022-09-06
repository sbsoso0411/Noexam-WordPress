<?php
namespace BaseCRM;

class TasksServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tasks, 'all'));
  }
  
  public function testCreateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tasks, 'create'));
  }
  
  public function testGetMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tasks, 'get'));
  }
  
  public function testUpdateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tasks, 'update'));
  }
  
  public function testDestroyMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->tasks, 'destroy'));
  }

  public function testAll()
  {
    $tasks = self::$client->tasks->all(['page' => 1]);
    $this->assertInternalType('array', $tasks);
 
  }

  public function testCreate()
  {
    $this->assertInternalType('array', self::$task);
    $this->assertGreaterThanOrEqual(1, count(self::$task));
 
  }

  public function testGet()
  {
    $foundTask = self::$client->tasks->get(self::$task['id']);
    $this->assertInternalType('array', $foundTask);
    $this->assertEquals($foundTask['id'], self::$task['id']);
 
  }

  public function testUpdate()
  {
    $updatedTask = self::$client->tasks->update(self::$task['id'], self::$task);
    $this->assertInternalType('array', $updatedTask);
    $this->assertEquals($updatedTask['id'], self::$task['id']);
 
  }

  public function testDestroy()
  {
    $newTask = self::createTask();
    $this->assertTrue(self::$client->tasks->destroy($newTask['id']));
 
  }
}  
