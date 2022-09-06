<?php
namespace BaseCRM;

class Configuration
{
  // @version 1.1.1 Current stable version.
  const VERSION = "1.2.0";

  const PRODUCTION_URL = "https://api.getbase.com";
  const URL_REGEXP = "/\b(?:(?:https?|http):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

  // @var string Personal access token
  public $accessToken;

  // @var string Base url for the api.
  public $baseUrl;
  
  // @var string Client user agent.
  public $userAgent;
  
  // @var integer Request timeout.
  public $timeout;
  
  // @var boolean Whether to verify ssl or not.
  public $verifySSL;
  
  // @var boolean Verbose/debug mode.
  public $verbose;

  /**
   * Instaniate a new Client configuration.
   *
   * @param array $config Client configuration settings
   *    - accessToken: Personal access token
   *    - baseUrl: Base url for the api. Default: "https://api.getbase.com"
   *    - userAgent: Client user agent. Default: "BaseCRM/v2 PHP/{BaseCRM::VERSION}"
   *    - timeout: Request timeout. Default: 30 seconds
   *    - verifySSL: Whether to verify ssl or not. Default: true
   *    - verbose: Verbose/debug mode. Default: false
   *
   * @throws \BaseCRM\Errors\ConfigurationError if no access token provided
   */
  public function __construct(array $options = [])
  {
    if (!isset($options['accessToken']))  throw new Errors\ConfigurationError($this->_accessTokenIsMissing());

    $this->accessToken =  $options['accessToken'];
    $this->baseUrl = isset($options['baseUrl']) ? $options['baseUrl'] : Configuration::PRODUCTION_URL;
    $this->userAgent = isset($options['userAgent']) ? $options['userAgent'] : "BaseCRM/v2 PHP/" . self::VERSION;
    $this->timeout = isset($options['timeout']) ? $options['timeout'] : 30;
    $this->verifySSL = isset($options['verifySSL']) ? $options['verifySSL'] : true;
    $this->verbose = isset($options['verbose']) ? $options['verbose'] : false;
  }

  /**
   * Checks if provided configuration is valid.
   *
   * @throws \BaseCRM\Errors\ConfigurationError if provided access token is invalid - contains disallowed characters
   * @throws \BaseCRM\Errors\ConfigurationError if provided access token is invalid - has invalid length
   * @throws \BaseCRM\Errors\ConfigurationError if provided base url is invalid
   *
   * @return boolean
   */
  public function isValid()
  {
    if (!is_string($this->accessToken))
    {
      $msg = 'Provided access token is invalid as it is not a string';
      throw new Errors\ConfigurationError($msg);
    }

    if (preg_match('/\s/', $this->accessToken))
    {
      $msg = 'Provided access token is invalid '
        . 'as it contains disallowed chracters. '
        . 'Please double-check your access token.';
      throw new Errors\ConfigurationError($msg);
    } 

    if (strlen($this->accessToken) != 64)
    {
      $msg = 'Provided access token is invalid '
        . 'as it contains disallowed characters. '
        . 'Please double-check your access token.';
      throw new Errors\ConfigurationError($msg);
    }

    if (!is_string($this->baseUrl) || !preg_match(Configuration::URL_REGEXP, $this->baseUrl))
    {
      $msg = 'Provided base url is invalid '
        . 'as it is not a valid URI. '
        . 'Please make sure it includes the schema part, both http and https are accepted, '
        . 'and the hierarchical part.';
      throw new Errors\ConfigurationError($msg);
    }

    return true;
  }

  /**
   * @ignore
   */
  protected function _accessTokenIsMissing()
  {
    $msg = 'No access token provided. '
      . 'Set your access token during client initialization using: '
      . '"new \\BaseCRM\\Client([\'accessToken\' => \'<YOUR_PERSONAL_ACCCESS_TOKEN\'])"';
    return $msg; 
  }
}
