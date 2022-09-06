<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\AssociatedContactsService
 *
 * Class used to make actions related to AssociatedContact resource.
 * 
 * @package BaseCRM
 */
class AssociatedContactsService
{
  // @var array Allowed attribute names.
  protected static $keysToPersist = ['contact_id', 'role'];

  protected $httpClient;

  /**
   * Instantiate a new AssociatedContactsService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve deal's associated contacts
   *
   * get '/deals/{deal_id}/associated_contacts'
   * 
   * Returns all deal associated contacts
   *
   * @param integer $deal_id Unique identifier of a Deal
   * @param array $options Search options
   * 
   * @return array The list of AssociatedContacts for the first page, unless otherwise specified.
   */
  public function all($deal_id, $options = [])
  {
    list($code, $associated_contacts) = $this->httpClient->get("/deals/{$deal_id}/associated_contacts", $options);
    return $associated_contacts;  
  }

  /**
   * Create an associated contact
   *
   * post '/deals/{deal_id}/associated_contacts'
   * 
   * Creates a deal's associated contact and its role
   * If the specified deal or contact does not exist, the request will return an error
   *
   * @param integer $deal_id Unique identifier of a Deal
   * @param array $associatedContact This array's attributes describe the object to be created.
   * 
   * @return array The resulting object representing created resource.
   */
  public function create($deal_id, array $associatedContact)
  {
    $attributes = array_intersect_key($associatedContact, array_flip(self::$keysToPersist));
 
    list($code, $createdAssociatedContact) = $this->httpClient->post("/deals/{$deal_id}/associated_contacts", $attributes);
    return $createdAssociatedContact; 
  }

  /**
   * Remove an associated contact
   *
   * delete '/deals/{deal_id}/associated_contacts/{contact_id}'
   * 
   * Remove a deal's associated contact
   * If a deal with the supplied unique identifier does not exist, it returns an error
   * This operation cannot be undone
   *
   * @param integer $deal_id Unique identifier of a Deal
   * @param integer $contact_id Unique identifier of a Contact
   * 
   * @return boolean Status of the operation.
   */
  public function destroy($deal_id, $contact_id)
  {
    list($code, $payload) = $this->httpClient->delete("/deals/{$deal_id}/associated_contacts/{$contact_id}");
    return $code == 204;
  }
}
