<?php
namespace BaseCRM;

use Exception;

/**
 * Parent class for every test case.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
  protected static $accessToken = null;
  protected static $baseUrl = null;
  protected static $client = null;

  protected static $account = null;
  protected static $associatedContact = null;
  protected static $contact = null;
  protected static $deal = null;
  protected static $lead = null;
  protected static $lossReason = null;
  protected static $note = null;
  protected static $source = null;
  protected static $tag = null;
  protected static $task = null;
  protected static $user = null;

  public static function setUpBeforeClass()
  {
    self::$accessToken = self::getAccessToken();
    self::$baseUrl = self::getBaseUrl();
    self::$client = new Client([
      'accessToken' => self::$accessToken,
      'baseUrl' => self::$baseUrl,
      'userAgent' => "BaseCRM/v2 PHP/" . Configuration::VERSION . '+tests',
      'verbose' => true,
    ]);
    
    self::$account = self::$client->accounts->self();
    self::$associatedContact = self::createAssociatedContact();
    self::$contact = self::createContact();
    self::$deal = self::createDeal();
    self::$lead = self::createLead();
    self::$lossReason = self::createLossReason();
    self::$note = self::createNote();
    self::$source = self::createSource();
    self::$tag = self::createTag();
    self::$task = self::createTask();
    self::$user = self::$client->users->self();
  }

  /**
   * @ignore
   */
  protected static function getAccessToken()
  {
    $token = getenv("BASECRM_ACCESS_TOKEN");
    if (!$token) throw new Exception('"BASECRM_ACCESS_TOKEN" environment variable has not been found.');
    return $token;
  }

  protected static function getBaseUrl()
  {
    $url = getenv("BASECRM_BASE_URL");
    if(!$url) $url = "https://api.getbase.com";
    return $url;
  }

  protected static function createAssociatedContact(array $attributes = [])
  {
    $deal_id = self::createDeal()['id'];
    $associatedContact = [
      'role' => "involved",
      'contact_id' => self::createContact()['id'],
    ];
    $associatedContact = self::$client->associatedContacts->create($deal_id, array_merge($associatedContact, $attributes));

    $associatedContact['deal_id'] = $deal_id;
    return $associatedContact;
  }

  protected static function createContact(array $attributes = [])
  {
    $contact = [
      'description' => "I know him via Tom",
      'email' => "mark@designservices.com",
      'facebook' => "mjohnson",
      'fax' => "+44-208-1234567",
      'first_name' => 'Mark' . rand(),
      'industry' => "Design Services",
      'is_organization' => false,
      'last_name' => 'Johnson' . rand(),
      'linkedin' => "mjohnson",
      'mobile' => "508-778-6516",
      'name' => 'Design Services Company' . rand(),
      'phone' => "508-778-6516",
      'skype' => "mjohnson",
      'tags' => ["important"],
      'title' => "CEO",
      'twitter' => "mjohnson",
      'website' => "www.designservices.com",
    ];
    $contact = self::$client->contacts->create(array_merge($contact, $attributes));

    return $contact;
  }

  protected static function createDeal(array $attributes = [])
  {
    $deal = [
      'currency' => "EUR",
      'dropbox_email' => "dropbox@4e627bcd.deals.futuresimple.com",
      'hot' => true,
      'name' => 'Website Redesign' . rand(),
      'tags' => ["important"],
      'value' => 1000,
      'contact_id' => self::createContact()['id'],
    ];
    $deal = self::$client->deals->create(array_merge($deal, $attributes));

    return $deal;
  }

  protected static function createDealWithDecimalValue(array $attributes = [])
  {
    $deal = [
      'currency' => "EUR",
      'dropbox_email' => "dropbox@4e627bcd.deals.futuresimple.com",
      'hot' => true,
      'name' => 'Website Redesign with decimal value' . rand(),
      'tags' => ["important"],
      'value' => "11.12",
      'contact_id' => self::createContact()['id'],
    ];

    $originalClient = self::$client;
    self::$client = ""; #$this->getMock('\BaseCRM\Client');

    $deal = self::$client->deals->create(array_merge($deal, $attributes));

    return $deal;
  }

  protected static function createLead(array $attributes = [])
  {
    $lead = [
      'description' => "I know him via Tom",
      'email' => "mark@designservices.com",
      'facebook' => "mjohnson",
      'fax' => "+44-208-1234567",
      'first_name' => 'Mark' . rand(),
      'industry' => "Design Services",
      'last_name' => 'Johnson' . rand(),
      'linkedin' => "mjohnson",
      'mobile' => "508-778-6516",
      'phone' => "508-778-6516",
      'skype' => "mjohnson",
      'status' => "Unqualified",
      'tags' => ["important"],
      'title' => "CEO",
      'twitter' => "mjohnson",
      'website' => "www.designservices.com",
    ];
    $lead = self::$client->leads->create(array_merge($lead, $attributes));

    return $lead;
  }

  protected static function createLossReason(array $attributes = [])
  {
    $lossReason = [
      'name' => 'We were too expensive' . rand(),
    ];
    $lossReason = self::$client->lossReasons->create(array_merge($lossReason, $attributes));

    return $lossReason;
  }

  protected static function createNote(array $attributes = [])
  {
    $note = [
      'content' => "Highly important.",
      'resource_id' => self::createContact()['id'],
      'resource_type' => 'contact',
    ];
    $note = self::$client->notes->create(array_merge($note, $attributes));

    return $note;
  }

  protected static function createSource(array $attributes = [])
  {
    $source = [
      'name' => 'Word of mouth' . rand(),
    ];
    $source = self::$client->sources->create(array_merge($source, $attributes));

    return $source;
  }

  protected static function createTag(array $attributes = [])
  {
    $tag = [
      'name' => 'publisher' . rand(),
      'resource_type' => 'contact',
    ];
    $tag = self::$client->tags->create(array_merge($tag, $attributes));

    return $tag;
  }

  protected static function createTask(array $attributes = [])
  {
    $task = [
      'content' => "Contact Tom",
      'due_date' => "2014-09-27T16:32:56+00:00",
      'remind_at' => "2014-09-29T15:32:56+00:00",
      'resource_id' => self::createContact()['id'],
      'resource_type' => 'contact',
    ];
    $task = self::$client->tasks->create(array_merge($task, $attributes));

    return $task;
  }
}
