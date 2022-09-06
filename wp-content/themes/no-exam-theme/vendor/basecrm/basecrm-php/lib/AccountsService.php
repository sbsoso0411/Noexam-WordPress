<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\AccountsService
 *
 * Class used to make actions related to Account resource.
 * 
 * @package BaseCRM
 */
class AccountsService
{
  protected $httpClient;

  /**
   * Instantiate a new AccountsService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve account details
   *
   * get '/accounts/self'
   * 
   * Returns detailed information about your account
   *
   * 
   * @return array Resource object.
   */
  public function self()
  {
    list($code, $resource) = $this->httpClient->get("/accounts/self");
    return $resource;
  }
}
