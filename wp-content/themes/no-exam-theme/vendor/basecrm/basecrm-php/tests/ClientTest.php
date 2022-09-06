<?php
namespace BaseCRM;

/**
 * @covers \BaseCRM\Client
 */
class ClientTest extends TestCase
{
   
  public function testAccountPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'accounts'));
  }
   
  public function testAssociatedContactPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'associatedContacts'));
  }
   
  public function testContactPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'contacts'));
  }
   
  public function testDealPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'deals'));
  }
   
  public function testLeadPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'leads'));
  }
   
  public function testLossReasonPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'lossReasons'));
  }
   
  public function testNotePropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'notes'));
  }
   
  public function testPipelinePropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'pipelines'));
  }
   
  public function testSourcePropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'sources'));
  }
   
  public function testStagePropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'stages'));
  }
   
  public function testTagPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'tags'));
  }
   
  public function testTaskPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'tasks'));
  }
   
  public function testUserPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'users'));
  }

  public function testSyncPropertyExists()
  {
    $this->assertTrue(property_exists(self::$client, 'sync'));
  }
}
