<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\LossReasonsService
 *
 * Class used to make actions related to LossReason resource.
 * 
 * @package BaseCRM
 */
class LossReasonsService
{
  // @var array Allowed attribute names.
  protected static $keysToPersist = ['name'];

  protected $httpClient;

  /**
   * Instantiate a new LossReasonsService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve all reasons
   *
   * get '/loss_reasons'
   * 
   * Returns all deal loss reasons available to the user according to the parameters provided
   *
   * @param array $options Search options
   * 
   * @return array The list of LossReasons for the first page, unless otherwise specified.
   */
  public function all($options = [])
  {
    list($code, $loss_reasons) = $this->httpClient->get("/loss_reasons", $options);
    return $loss_reasons;  
  }

  /**
   * Create a loss reason
   *
   * post '/loss_reasons'
   * 
   * Create a new loss reason
   * <figure class="notice">
   * Loss reason's name **must** be unique
   * </figure>
   *
   * @param array $lossReason This array's attributes describe the object to be created.
   * 
   * @return array The resulting object representing created resource.
   */
  public function create(array $lossReason)
  {
    $attributes = array_intersect_key($lossReason, array_flip(self::$keysToPersist));
 
    list($code, $createdLossReason) = $this->httpClient->post("/loss_reasons", $attributes);
    return $createdLossReason; 
  }

  /**
   * Retrieve a single reason
   *
   * get '/loss_reasons/{id}'
   * 
   * Returns a single loss reason available to the user by the provided id
   * If a loss reason with the supplied unique identifier does not exist, it returns an error
   *
   * @param integer $id Unique identifier of a LossReason
   * 
   * @return array Searched LossReason.
   */
  public function get($id)
  {
    list($code, $loss_reason) = $this->httpClient->get("/loss_reasons/{$id}");
    return $loss_reason;
  }
 
  /**
   * Update a loss reason
   *
   * put '/loss_reasons/{id}'
   * 
   * Updates a loss reason information
   * If the specified loss reason does not exist, the request will return an error
   * <figure class="notice">
   * If you want to update loss reason you **must** make sure name of the reason is unique
   * </figure>
   *
   * @param integer $id Unique identifier of a LossReason
   * @param array $lossReason This array's attributes describe the object to be updated.
   * 
   * @return array The resulting object representing updated resource.
   */
  public function update($id, array $lossReason)
  {
    $attributes = array_intersect_key($lossReason, array_flip(self::$keysToPersist)); 
 
    list($code, $updatedLossReason) = $this->httpClient->put("/loss_reasons/{$id}", $attributes);
    return $updatedLossReason; 
  }

  /**
   * Delete a reason
   *
   * delete '/loss_reasons/{id}'
   * 
   * Delete an existing loss reason
   * If the reason with supplied unique identifier does not exist it returns an error
   * This operation cannot be undone
   *
   * @param integer $id Unique identifier of a LossReason
   * 
   * @return boolean Status of the operation.
   */
  public function destroy($id)
  {
    list($code, $payload) = $this->httpClient->delete("/loss_reasons/{$id}");
    return $code == 204;
  }
}
