<?php
namespace BaseCRM;

class AccountsServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testSelfMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->accounts, 'self'));
  }

  public function testSelf()
  {
    $resource = self::$client->accounts->self();
    $this->assertInternalType('array', $resource);
 
  }
}  
