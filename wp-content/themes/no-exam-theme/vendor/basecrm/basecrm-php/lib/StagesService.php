<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\StagesService
 *
 * Class used to make actions related to Stage resource.
 * 
 * @package BaseCRM
 */
class StagesService
{
  protected $httpClient;

  /**
   * Instantiate a new StagesService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve all stages
   *
   * get '/stages'
   * 
   * Returns all stages available to the user, according to the parameters provided
   *
   * @param array $options Search options
   * 
   * @return array The list of Stages for the first page, unless otherwise specified.
   */
  public function all($options = [])
  {
    list($code, $stages) = $this->httpClient->get("/stages", $options);
    return $stages;  
  }
}
