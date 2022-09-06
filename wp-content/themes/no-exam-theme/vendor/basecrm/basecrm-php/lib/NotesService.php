<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\NotesService
 *
 * Class used to make actions related to Note resource.
 * 
 * @package BaseCRM
 */
class NotesService
{
  // @var array Allowed attribute names.
  protected static $keysToPersist = ['content', 'resource_id', 'resource_type'];

  protected $httpClient;

  /**
   * Instantiate a new NotesService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve all notes
   *
   * get '/notes'
   * 
   * Returns all notes available to the user, according to the parameters provided
   *
   * @param array $options Search options
   * 
   * @return array The list of Notes for the first page, unless otherwise specified.
   */
  public function all($options = [])
  {
    list($code, $notes) = $this->httpClient->get("/notes", $options);
    return $notes;  
  }

  /**
   * Create a note
   *
   * post '/notes'
   * 
   * Create a new note and associate it with one of the resources listed below:
   * * [Leads](/docs/rest/reference/leads)
   * * [Contacts](/docs/rest/reference/contacts)
   * * [Deals](/docs/rest/reference/deals)
   *
   * @param array $note This array's attributes describe the object to be created.
   * 
   * @return array The resulting object representing created resource.
   */
  public function create(array $note)
  {
    $attributes = array_intersect_key($note, array_flip(self::$keysToPersist));
 
    list($code, $createdNote) = $this->httpClient->post("/notes", $attributes);
    return $createdNote; 
  }

  /**
   * Retrieve a single note
   *
   * get '/notes/{id}'
   * 
   * Returns a single note available to the user, according to the unique note ID provided
   * If the note ID does not exist, this request will return an error
   *
   * @param integer $id Unique identifier of a Note
   * 
   * @return array Searched Note.
   */
  public function get($id)
  {
    list($code, $note) = $this->httpClient->get("/notes/{$id}");
    return $note;
  }
 
  /**
   * Update a note
   *
   * put '/notes/{id}'
   * 
   * Updates note information
   * If the note ID does not exist, this request will return an error
   *
   * @param integer $id Unique identifier of a Note
   * @param array $note This array's attributes describe the object to be updated.
   * 
   * @return array The resulting object representing updated resource.
   */
  public function update($id, array $note)
  {
    $attributes = array_intersect_key($note, array_flip(self::$keysToPersist)); 
 
    list($code, $updatedNote) = $this->httpClient->put("/notes/{$id}", $attributes);
    return $updatedNote; 
  }

  /**
   * Delete a note
   *
   * delete '/notes/{id}'
   * 
   * Delete an existing note
   * If the note ID does not exist, this request will return an error
   * This operation cannot be undone
   *
   * @param integer $id Unique identifier of a Note
   * 
   * @return boolean Status of the operation.
   */
  public function destroy($id)
  {
    list($code, $payload) = $this->httpClient->delete("/notes/{$id}");
    return $code == 204;
  }
}
