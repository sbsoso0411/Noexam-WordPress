<?php
namespace BaseCRM;

class SyncTest extends \PHPUnit_Framework_TestCase
{
  public $deviceUUID = '6dadcec8-6e61-4691-b318-1aab27b8fecf';
  public $sessionId = '29f2aeeb-8d68-4ea7-95c3-a2c8e151f5a3';

  public function testFetchMethodExists()
  {
    $client = $this->getMockBuilder('\BaseCRM\Client')
                 ->disableOriginalConstructor()
                 ->getMock();

    $sync = new Sync($client, $this->deviceUUID);
    $this->assertTrue(method_exists($sync, 'fetch'));
  }

  public function testSynchronizationFlow()
  {
    $session = [
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

    $queueItems = [
      [
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
      [
        'data' => [
          'id' => 1
        ],
        'meta' => [
          'type' => 'source',
          'sync' => [
            'event_type' => 'created',
            'ack_key' => 'Source-1234-1',
            'revision' => 1
          ]
        ]
      ]
    ];

    $ackKeys = array_map(function ($item) {
      return $item['meta']['sync']['ack_key'];
    }, $queueItems);

    $client = $this->getMockBuilder('\BaseCRM\Client')
                 ->disableOriginalConstructor()
                 ->getMock();

    $syncService = $this->getMockBuilder('\BaseCRM\SyncService')
                        ->disableOriginalConstructor()
                        ->getMock();

    $syncService->expects($this->once())
                ->method('start')
                ->with($this->deviceUUID)
                ->will($this->returnValue($session));
    $syncService->expects($this->exactly(2))
                ->method('fetch')
                ->with($this->deviceUUID, $session['id'])
                ->will($this->onConsecutiveCalls($queueItems, []));
    $syncService->expects($this->at(1))
                ->method('fetch')
                ->with($this->deviceUUID, $session['id'])
                ->will($this->returnValue([]));

    $client->sync = $syncService;

    $sync = new Sync($client, $this->deviceUUID);

    $counter = 0;

    $sync->fetch(function($meta, $data) use (&$counter) {
      $this->assertTrue(isset($meta['sync']));
      $this->assertEquals($data['id'], 1);
      $counter += 1;  
    });

    $this->assertEquals($counter, 2);
  }
}
