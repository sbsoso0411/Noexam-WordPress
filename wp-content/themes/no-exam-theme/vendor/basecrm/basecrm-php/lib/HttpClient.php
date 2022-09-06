<?php
namespace BaseCRM;

class HttpClient
{

  const API_VERSION_PREFIX = "/v2"; 
    
  // @ignore
  protected $config;

  public function __construct(Configuration $config)
  {
    $this->config = $config;
  }
  
  /**
   * Perform a GET request
   *
   * @param string $url Url to send the request to.
   * @param array $params Query params to send with the request. They are converted to a query string and attached to the url.
   * @param array $options Additional request's options.
   *  @option array 'headers' Additional headers to send with the request
   *  @option boolean 'raw' Indicator whether to wrap and uwrap request in the envelope.
   *
   * @return array Array where first element is http status code and the second one is resource.
   */
  public function get($url, array $params = null, array $options = array())
  {
    return $this->request('GET', $url, $params, null, $options);
  }

  /**
   * Perform a POST request
   *
   * @param string $url Url to send the request to.
   * @param array $body Body params to send with the request. They are converted to json and sent in the body.
   * @param array $options Additional request's options.
   *  @option array 'headers' Additional headers to send with the request
   *  @option boolean 'raw' Indicator whether to wrap and uwrap request in the envelope.
   *
   * @return array Array where first element is http status code and the second one is resource.
   */
  public function post($url, array $body = null, array $options = array())
  {
    return $this->request('POST', $url, null, $body, $options);
  }

  /**
   * Perform a POST request
   *
   * @param string $url Url to send the request to.
   * @param array $body Body params to send with the request. They are converted to json and sent in the body.
   * @param array $options Additional request's options.
   *  @option array 'headers' Additional headers to send with the request
   *  @option boolean 'raw' Indicator whether to wrap and uwrap request in the envelope.
   *
   * @return array Array where first element is http status code and the second one is resource.
   */
  public function put($url, array $body = null, array $options = array())
  {
    return $this->request('PUT', $url, null, $body, $options);
  }

  /**
   * Perform a DELETE request
   *
   * @param string $url Url to send the request to.
   * @param array $params Query params to send with the request. They are converted to a query string and attached to the url.
   * @param array $options Additional request's options.
   *  @option array 'headers' Additional headers to send with the request
   *  @option boolean 'raw' Indicator whether to wrap and uwrap request in the envelope.
   *
   * @return array Array where first element is http status code and the second one is resource.
   */
  public function delete($url, array $params = null, array $options = array())
  {
    return $this->request('DELETE', $url, $params, null, $options);
  }

  /**
   * Perform an HTTP request
   *
   * @param string $method HTTP method.
   * @param string $url Url to send the request to.
   * @param array $params Query params to send with the request. They are converted to a query string and attached to the url.
   * @param array $body Body params to send with the request. They are converted to json and sent in the body.
   * @param array $options Additional request's options.
   *  @option array 'headers' Additional headers to send with the request
   *  @option boolean 'raw' Indicator whether to wrap and uwrap request in the envelope.
   *
   * @throws \BaseCRM\Errors\ConnectionError if connnection error occurrs e.g. timeout, dns issue etc.
   * @throws \BaseCRM\Errors\RequestError if request was invalid
   * @throws \BaseCRM\Errors\ResourceError if request's payload validation failed
   * @throws \BaseCRM\Errors\ServerError if server error occurred
   *
   * @return array Array where first element is http status code and the second one is resource.
   */
  public function request($method, $url, array $params = null, array $body = null, array $options = array())
  {
    $method = strtolower($method);

    $defaultHeaders = [
      'User-Agent' => $this->config->userAgent,
      'Authorization' => "Bearer {$this->config->accessToken}",
      'Accept' => 'application/json'
    ];

    $userHeaders = ($options && isset($options['headers']) && is_array($options['headers'])) ? $options['headers'] : array();

    $headers = array_merge($defaultHeaders, $userHeaders);

    if ($body && in_array($method, ['post', 'put', 'patch']))
    {
      $headers['Content-Type'] = 'application/json';
    }

    $rawHeaders = array();
    foreach ($headers as $header => $value)
    {
      $rawHeaders[] = $header . ': ' . $value;
    }

    if ($params) {
        $query = http_build_query($this->encodeQueryParams($params));
        $url = "{$url}?{$query}";
    }

    $absUrl = $this->config->baseUrl . self::API_VERSION_PREFIX . $url;
    $raw = ($options && isset($options['raw'])) ? !!$options['raw'] : false;

    $curl = curl_init();
    if ($this->config->verbose) curl_setopt($curl, CURLOPT_VERBOSE, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->config->verifySSL);
    curl_setopt($curl, CURLOPT_URL, $absUrl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $rawHeaders);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    if ($body && in_array($method, ['post', 'put', 'patch']))
    {
      $envelope = $raw ? $body : $this->wrapEnvelope($body);
      $payload = json_encode($this->encodePayload($envelope));
      curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    }
    $resp = curl_exec($curl);

    if ($resp === false)
    {
      $errno = curl_errno($curl);
      $error_message = curl_error($curl);
      curl_close($curl);

      throw new Errors\ConnectionError($errno, $error_message);
    }

    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $resource = null;
    if ($resp != null)
    {
      $decodedResponse = $this->handleResponse($code, $resp);
      $resource = $raw ? $decodedResponse : $this->unwrapEnvelope($decodedResponse);
    }    
    return array($code, $resource);
  }

  /**
   * Peform envelope wrapping
   *
   * @param array $body Associative array representing resource to wrap.
   * @return array
   *
   * @ignore
   */
  protected function wrapEnvelope(array $body)
  {
    return ['data' => $body];
  }

  /**
   * Perform envelope unwrapping
   * 
   * @param array $body Associative array after json decoding.
   * @return mixed Either single resource or array of associative resources.
   *
   * @ignore
   */
  protected function unwrapEnvelope(array $body)
  {
    if (isset($body['data']))
    {
      return $body['data'];
    }
    else if (isset($body['items']))
    {
      $items = array();
      foreach ($body['items'] as $item)
      {
        $items[] = $item;
      }

      return $items;
    }
    return $body;
  }

  /**
   * Ensure consistency in encoding between native PHP types and Base API expected types.
   *
   * @param array $params Associative array of either wrapped or unwrapped payload to be send.
   * @return array Associative array with properly handled data types
   *
   * @ignore
   */
  protected function encodePayload(array $params)
  {
    $encoded = [];
    foreach ($params as $key => $value)
    {
      if (is_array($value))                 $encoded[$key] = $this->encodePayload($value);
      else if ($value instanceof \DateTime) $encoded[$key] = $value->format(\DateTime::ISO8601);
      else                                  $encoded[$key] = $value;
    }

    return $encoded;
  } 

  /**
   * Ensure consistency in encoding between native PHP types and Base API expected query type format.
   *
   * @param array $params Associative array of query parameters to be send.
   * @return array Associative array with properly handled data types
   *
   * @ignore
   */
  protected function encodeQueryParams(array $params)
  {
    $encoded = [];
    foreach ($params as $key => $value)
    {
      if (is_array($value))                 $encoded[$key] = $this->encodeQueryParams($value);
      else if (is_bool($value))             $encoded[$key] = $value ? 'true' : 'false';
      else if ($value instanceof \DateTime) $encoded[$key] = $value->format(\DateTime::ISO8601);
      else                                  $encoded[$key] = $value;
    }

    return $encoded;
  } 


  /**
   * @param integer $code Http response code
   * @param string $rawResponse Http response payload
   *
   * @throws \BaseCRM\Errors\RequestError if request was invalid
   * @throws \BaseCRM\Errors\ResourceError if request's payload validation failed
   * @throws \BaseCRM\Errors\ServerError if server error occurred
   *
   * @return mixed Decoded response 
   *
   * @ignore
   */
  protected function handleResponse($code, $rawResponse)
  {
    try 
    {
      $response = json_decode($rawResponse, true);
    }
    catch (Exception $e)
    {
      $msg = "Unknown error occurred. The response should be a json response. "
        . "HTTP response code={$code}. "
        . "HTTP response body={$rawResponse}.";
      throw new Exception($msg);
    }

    if ($code < 200 || $code >= 400)
    {
      $this->handleErrorResponse($code, $rawResponse, $response);
    }

    return $response;
  }

  /**
   * @ignore
   */
  protected function handleErrorResponse($code, $rawResponse, $response)
  {
    switch(true)
    {
    case $code == 422:
      throw new Errors\ResourceError($code, $response);
    case $code == 429:
      throw new Errors\RateLimitError();
    case $code >= 400 && $code < 500:
      throw new Errors\RequestError($code, $response);
    case $code >= 500 && $code < 600:
      throw new Errors\ServerError($code, $response);
    default:
      $msg = "Unknown HTTP error response. "
        . "HTTP response code={$code}. "
        . "HTTP response body={$rawResponse}.";
      throw new Exception($msg);
    }
  }
}
