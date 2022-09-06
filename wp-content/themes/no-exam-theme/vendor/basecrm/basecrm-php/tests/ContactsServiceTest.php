<?php
namespace BaseCRM;

class ContactsServiceTest extends TestCase
{
  public function setUp()
  {
    parent::setUp();
  }
  
  public function testAllMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->contacts, 'all'));
  }
  
  public function testCreateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->contacts, 'create'));
  }
  
  public function testGetMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->contacts, 'get'));
  }
  
  public function testUpdateMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->contacts, 'update'));
  }
  
  public function testDestroyMethodExists()
  {
    $this->assertTrue(method_exists(self::$client->contacts, 'destroy'));
  }

  public function testAll()
  {
    $contacts = self::$client->contacts->all(['page' => 1]);
    $this->assertInternalType('array', $contacts);
 
  }

  public function testCreate()
  {
    $this->assertInternalType('array', self::$contact);
    $this->assertGreaterThanOrEqual(1, count(self::$contact));
 
  }

  public function testGet()
  {
    $foundContact = self::$client->contacts->get(self::$contact['id']);
    $this->assertInternalType('array', $foundContact);
    $this->assertEquals($foundContact['id'], self::$contact['id']);
 
  }

  public function testUpdate()
  {
    $updatedContact = self::$client->contacts->update(self::$contact['id'], self::$contact);
    $this->assertInternalType('array', $updatedContact);
    $this->assertEquals($updatedContact['id'], self::$contact['id']);
 
  }

  public function testDestroy()
  {
    $newContact = self::createContact();
    $this->assertTrue(self::$client->contacts->destroy($newContact['id']));
 
  }
}  
