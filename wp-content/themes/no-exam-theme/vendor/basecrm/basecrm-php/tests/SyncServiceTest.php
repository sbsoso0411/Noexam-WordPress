<?php
namespace BaseCRM;

class SyncServiceTest extends \PHPUnit_Framework_TestCase
{
  public $deviceUUID = '6dadcec8-6e61-4691-b318-1aab27b8fecf';
  public $sessionId = '29f2aeeb-8d68-4ea7-95c3-a2c8e151f5a3';

  public function testMethodsExist()
  {
    $http = $this->getMockBuilder('\BaseCRM\HttpClient')
                 ->disableOriginalConstructor()
                 ->getMock();
    $sync = new SyncService($http);

    foreach (['start', 'fetch', 'ack'] as $method)
    {
      $this->assertTrue(method_exists($sync, $method));
    }
  }

  public function testStartNothingNew()
  {
    $httpResponse = [204, null];

    $http = $this->getMockBuilder('\BaseCRM\HttpClient')
                 ->disableOriginalConstructor()
                 ->getMock();

    $http->expects($this->once())
          ->method('post')
          ->with('/sync/start', null, ['headers' => ['X-Basecrm-Device-UUID' => $this->deviceUUID]])
          ->will($this->returnValue($httpResponse));

    $sync = new SyncService($http);
    $this->assertNull($sync->start($this->deviceUUID));
  }

  public function testStartGotSession()
  {
    $payload = [
      'id' => $this->sessionId,
      'queues' => [
        'data' => [
          'name' => 'main',
          'pages' => 1,
          'total_count' => 2
        ],
        'meta' => [
          'type' => 'sync_queue'
        ]
      ] 
    ];

    $httpResponse = [201, $payload];

    $http = $this->getMockBuilder('\BaseCRM\HttpClient')
                 ->disableOriginalConstructor()
                 ->getMock();

    $http->expects($this->once())
          ->method('post')
          ->with('/sync/start', null, ['headers' => ['X-Basecrm-Device-UUID' => $this->deviceUUID]])
          ->will($this->returnValue($httpResponse));

    $sync = new SyncService($http);
    $this->assertEquals($sync->start($this->deviceUUID), $payload);
  }

  public function testAck()
  {
    $httpResponse = [202, null];
    $ackKeys = [
      'User-1234-1',
      'Source-1234-1'
    ];

    $http = $this->getMockBuilder('\BaseCRM\HttpClient')
                 ->disableOriginalConstructor()
                 ->getMock();

    $http->expects($this->once())
          ->method('post')
          ->with('/sync/ack', ['ack_keys' => $ackKeys], ['headers' => ['X-Basecrm-Device-UUID' => $this->deviceUUID]])
          ->will($this->returnValue($httpResponse));

    $sync = new SyncService($http);

    $this->assertTrue($sync->ack($this->deviceUUID, $ackKeys));
  }

  public function testFetchNoMoreData()
  {
    $httpResponse = [204, null];

    $http = $this->getMockBuilder('\BaseCRM\HttpClient')
                 ->disableOriginalConstructor()
                 ->getMock();

    $http->expects($this->once())
          ->method('get')
          ->with("/sync/{$this->sessionId}/queues/main", 
                  null, 
                  [
                    'headers' => [
                      'X-Basecrm-Device-UUID' => $this->deviceUUID
                    ], 
                    'raw' => true])
          ->will($this->returnValue($httpResponse));

    $sync = new SyncService($http);
    $this->assertEquals($sync->fetch($this->deviceUUID, $this->sessionId), []);
  }

  public function testFetchGotData()
  {
    $payload = [
      'items' => [
        'data' => [
          'id' => 1
        ],
        'meta' => [
          'type' => 'user',
          'sync' => [
            'event_type' => 'created',
            'ack_key' => 'User-1234-1',
            'revision' => 1
          ]
        ]
      ],
      'meta' => [
        'type' => 'collection'
      ] 
    ];

    $httpResponse = [200, $payload];

    $http = $this->getMockBuilder('\BaseCRM\HttpClient')
                 ->disableOriginalConstructor()
                 ->getMock();

    $http->expects($this->once())
          ->method('get')
          ->with("/sync/{$this->sessionId}/queues/main", 
                  null, 
                  [
                    'headers' => [
                      'X-Basecrm-Device-UUID' => $this->deviceUUID
                    ], 
                    'raw' => true])
          ->will($this->returnValue($httpResponse));

    $sync = new SyncService($http);
    $this->assertEquals($sync->fetch($this->deviceUUID, $this->sessionId), $payload['items']);
  }
}
