<?php
namespace BaseCRM;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
  protected static $httpClient = null;

  public static function setUpBeforeClass()
  {
    self::$httpClient = new HttpClient(new Configuration([
      'accessToken' => str_repeat('1', 64) 
    ]));
  }

  public function testDateTimeEncoding()
  {
    $payload = [
      'updated_at' => new \DateTime('2016-02-25T01:02:03Z')
    ];
    
    $result = PHPUnitUtil::callMethod(self::$httpClient, 'encodePayload', Array($payload));
    $expectation = ['updated_at' => '2016-02-25T01:02:03+0000'];

    $this->assertEquals($result, $expectation);
  }

  public function testNestedDateTimeEncoding()
  {
    $payload = [
      'nested' => [
        'updated_at' => new \DateTime('2016-02-25T01:02:03Z')
      ],
    ];

    $result = PHPUnitUtil::callMethod(self::$httpClient, 'encodePayload', Array($payload));
    $expectation = ['nested' => ['updated_at' => '2016-02-25T01:02:03+0000']];

    $this->assertEquals($result, $expectation);
  }

  public function testQueryParamBooleansEncoding()
  {
    $params = [
      'active' => true,
      'deactivated' => false
    ];

    $result = PHPUnitUtil::callMethod(self::$httpClient, 'encodeQueryParams', Array($params));
    $expectation = ['active' => 'true', 'deactivated' => 'false'];

    $this->assertEquals($result, $expectation);
  }
}
