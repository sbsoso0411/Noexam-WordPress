<?php
namespace BaseCRM;

class StagesServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->stages, 'all'));
  }

  public function testAll()
  {
    $stages = self::$client->stages->all(['page' => 1]);
    $this->assertInternalType('array', $stages);
 
  }
}  
