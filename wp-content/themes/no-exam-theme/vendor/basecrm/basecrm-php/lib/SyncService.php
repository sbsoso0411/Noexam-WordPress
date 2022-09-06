<?php

namespace BaseCRM;

/**
 * BaseCRM\SyncService
 *
 * Class you use to access low-level actions related to Sync API.
 * 
 * @package BaseCRM
 */
class SyncService
{
  protected $httpClient;

  /**
   * Instantiate a new SyncService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Start synchronization flow
   *
   * post '/sync/start'
   * 
   * Starts a new synchronization session.
   * This is the first endpoint to call, in order to start a new synchronization session.
   *
   * @param string $deviceUUID Device's UUID for which to perform synchronization.
   * 
   * @return array The resulting object representing synchronization session or null if there is nothing to synchronize.
   */
  public function start($deviceUUID)
  {
    // @throws InvalidArgumentException
    $this->checkArgument($deviceUUID, 'deviceUUID');

    list($code, $session) = $this->httpClient->post('/sync/start', null, ['headers' => $this->buildHeaders($deviceUUID)]);

    if ($code == 204) return null;
    return $session;
  }

  /**
   * Get data from queue
   *
   * get '/sync/{sessionId}/queues/main'
   * 
   * Fetch fresh data from the named queue.
   * Using session identifier you call continously the `#fetch` method to drain the named queue.
   *
   * @param string $deviceUUID Device's UUID for which to perform synchronization.
   * @param string $sessionId Unique identifier of a synchronization session.
   * @param string $queue Queue name.
   * 
   * @return array The list of resources and associated meta data or an empty array if there is no more data to synchronize.
   */
  public function fetch($deviceUUID, $sessionId, $queue = 'main')
  {
    // @throws InvalidArgumentException
    $this->checkArgument($deviceUUID, 'deviceUUID');
    $this->checkArgument($sessionId, 'sessionId');
    $this->checkArgument($queue, 'queue');

    $options = [
      'headers' => $this->buildHeaders($deviceUUID),
      'raw' => true
    ];
    list($code, $root) = $this->httpClient->get("/sync/{$sessionId}/queues/{$queue}", null, $options);
    
    if ($code == 204) return [];
    return $root['items'];
  }

  /**
   * Acknowledge received data
   *
   * post '/sync/ack'
   * 
   * Send acknowledgement keys to let know the Sync service which data you have.
   * As you fetch new data, you need to send acknowledgement keys.
   *
   * @param string $deviceUUID Device's UUID for which to perform synchronization.
   * @param array $ackKeys The list of acknowledgement keys.
   * 
   * @return boolean Status of the operation.
   */
  public function ack($deviceUUID, array $ackKeys)
  {
    // @throws InvalidArgumentException
    $this->checkArgument($deviceUUID, 'deviceUUID');

    // fast path - nothing to acknowledge
    if (!$ackKeys) return true;

    $attributes = ['ack_keys' => $ackKeys];
    list($code,) = $this->httpClient->post('/sync/ack', $attributes, ['headers' => $this->buildHeaders($deviceUUID)]);
    return $code == 202;
  }

  /**
   * Performs string's uuid/id validation.
   *
   * @ignore
   */
  protected function checkArgument($argument, $argumentName)
  {
    if (!is_string($argument) || !trim($argument)) 
      throw new InvalidArgumentException("{$argumentName} argument must be a non-empty string. Input was: {$argument}");
  }

  /**
   * Builds array of headers required by Sync API.
   *
   * @ignore
   */
  protected function buildHeaders($deviceUUID)
  {
    return ['X-Basecrm-Device-UUID' => $deviceUUID];
  }
}
