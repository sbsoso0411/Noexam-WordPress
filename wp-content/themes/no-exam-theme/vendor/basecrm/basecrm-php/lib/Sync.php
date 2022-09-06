<?php

namespace BaseCRM;

/**
 * BaseCRM\Sync
 *
 * BaseCRM Sync API V2 high-level wrapper.
 *
 * @package BaseCRM
 */
class Sync
{
  /**
   * @var boolean Constant representing acknowledged state.
   */
  const ACK = true;

  /** 
   * @var boolean Constant representing not acknowledged state.
   */
  const NACK = false;

  /**
   * @var string Device's UUID.
   * @ignore
   */
  protected $deviceUUID;

  /**
   * @var \BaseCRM\Client BaseCRM client.
   * @ignore
   */
  protected $client;

  /**
   * Instantiate a new high-level Sync wrapper instance.
   * 
   * @param \BaseCRM\Client BaseCRM API V2 client instance. 
   * @param string $deviceUUID Device's UUID for which to perform synchronization.
   *
   * @throws InvalidArgumentException if deviceUUID is not a string
   */
  public function __construct(Client $client, $deviceUUID)
  {
    if (!is_string($deviceUUID) || !trim($deviceUUID)) 
      throw new InvalidArgumentException('deviceUUID argument must be a non-empty string');

    $this->client = $client;
    $this->deviceUUID = $deviceUUID;
  }

  /**
   * Perform a full syncrhonization flow.
   * See the following example:
   * 
   * <code>
   * <?php
   * $client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
   * $sync = new \BaseCRM\Sync($client, '<YOUR_DEVICES_UUID>');
   * $sync.fetch(function ($meta, $resource){
   *  $klass = classify($meta['type']); // e.g. \BaseCRM\User, \BaseCRM\Source etc..
   *  call_user_func($klass . $meta['sync']['event_type'], $resource) ? \BaseCRM\Sync::ACK : \BaseCRM\Sync::NACK;
   * });
   * ?>
   * </code>
   *
   * @param Closure $callback Callback that will be called for every item in queue.
   *  Takes two input arguments: synchronization meta data and associated resource. 
   *  It must return either ACK or NACK|null.
   */
  public function fetch(\Closure $callback)
  {
    // Set up a new synchronization session for a given device's UUID
    $session = $this->client->sync->start($this->deviceUUID);

    // Check if there is anything to synchronize
    if (!$session || !$session['id']) return;

    $sessionId = $session['id'];

    // Drain the main queue unitl there is no data (empty array)
    while (true)
    {
      // fetch the main queue
      $queueItems = $this->client->sync->fetch($this->deviceUUID, $sessionId);

      // nothing more to synchronize ?
      if (!$queueItems) break;

      // let client know about data and meta 
      $ackKeys = array();
      
      foreach ($queueItems as $item)
      {
        if ($callback($item['meta'], $item['data']))
        {
          $ackKeys[] = $item['meta']['sync']['ack_key'];
        }
      }
      
      // As we fetch new data, we need to send acknowledgement keys - if any
      if ($ackKeys) $this->client->sync->ack($this->deviceUUID, $ackKeys);
    }
  }
}
