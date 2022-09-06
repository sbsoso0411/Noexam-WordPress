<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\ContactsService
 *
 * Class used to make actions related to Contact resource.
 * 
 * @package BaseCRM
 */
class ContactsService
{
  // @var array Allowed attribute names.
  protected static $keysToPersist = ['address', 'contact_id', 'custom_fields', 'customer_status', 'description', 'email', 'facebook', 'fax', 'first_name', 'industry', 'is_organization', 'last_name', 'linkedin', 'mobile', 'name', 'owner_id', 'phone', 'prospect_status', 'skype', 'tags', 'title', 'twitter', 'website'];

  protected $httpClient;

  /**
   * Instantiate a new ContactsService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve all contacts
   *
   * get '/contacts'
   * 
   * Returns all contacts available to the user according to the parameters provided
   *
   * @param array $options Search options
   * 
   * @return array The list of Contacts for the first page, unless otherwise specified.
   */
  public function all($options = [])
  {
    list($code, $contacts) = $this->httpClient->get("/contacts", $options);
    return $contacts;  
  }

  /**
   * Create a contact
   *
   * post '/contacts'
   * 
   * Create a new contact
   * A contact may represent a single individual or an organization
   *
   * @param array $contact This array's attributes describe the object to be created.
   * 
   * @return array The resulting object representing created resource.
   */
  public function create(array $contact)
  {
    $attributes = array_intersect_key($contact, array_flip(self::$keysToPersist));
    if (isset($attributes['custom_fields']) && empty($attributes['custom_fields'])) unset($attributes['custom_fields']);
 
    list($code, $createdContact) = $this->httpClient->post("/contacts", $attributes);
    return $createdContact; 
  }

  /**
   * Retrieve a single contact
   *
   * get '/contacts/{id}'
   * 
   * Returns a single contact available to the user, according to the unique contact ID provided
   * If the specified contact does not exist, the request will return an error
   *
   * @param integer $id Unique identifier of a Contact
   * 
   * @return array Searched Contact.
   */
  public function get($id)
  {
    list($code, $contact) = $this->httpClient->get("/contacts/{$id}");
    return $contact;
  }
 
  /**
   * Update a contact
   *
   * put '/contacts/{id}'
   * 
   * Updates contact information
   * If the specified contact does not exist, the request will return an error
   * **Notice** When updating contact tags, you need to provide all tags
   * Any missing tag will be removed from a contact's tags
   *
   * @param integer $id Unique identifier of a Contact
   * @param array $contact This array's attributes describe the object to be updated.
   * 
   * @return array The resulting object representing updated resource.
   */
  public function update($id, array $contact)
  {
    $attributes = array_intersect_key($contact, array_flip(self::$keysToPersist)); 
    if (isset($attributes['custom_fields']) && empty($attributes['custom_fields'])) unset($attributes['custom_fields']);
 
    list($code, $updatedContact) = $this->httpClient->put("/contacts/{$id}", $attributes);
    return $updatedContact; 
  }

  /**
   * Delete a contact
   *
   * delete '/contacts/{id}'
   * 
   * Delete an existing contact
   * If the specified contact does not exist, the request will return an error
   * This operation cannot be undone
   *
   * @param integer $id Unique identifier of a Contact
   * 
   * @return boolean Status of the operation.
   */
  public function destroy($id)
  {
    list($code, $payload) = $this->httpClient->delete("/contacts/{$id}");
    return $code == 204;
  }
}
