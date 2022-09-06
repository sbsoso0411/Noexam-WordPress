<?php
namespace BaseCRM;

class PipelinesServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->pipelines, 'all'));
  }

  public function testAll()
  {
    $pipelines = self::$client->pipelines->all(['page' => 1]);
    $this->assertInternalType('array', $pipelines);
 
  }
}  
