<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\TagsService
 *
 * Class used to make actions related to Tag resource.
 * 
 * @package BaseCRM
 */
class TagsService
{
  // @var array Allowed attribute names.
  protected static $keysToPersist = ['name', 'resource_type'];

  protected $httpClient;

  /**
   * Instantiate a new TagsService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve all tags
   *
   * get '/tags'
   * 
   * Returns all tags available to the user, according to the parameters provided
   *
   * @param array $options Search options
   * 
   * @return array The list of Tags for the first page, unless otherwise specified.
   */
  public function all($options = [])
  {
    list($code, $tags) = $this->httpClient->get("/tags", $options);
    return $tags;  
  }

  /**
   * Create a tag
   *
   * post '/tags'
   * 
   * Creates a new tag
   * **Notice** the tag's name **must** be unique within the scope of the resource_type
   *
   * @param array $tag This array's attributes describe the object to be created.
   * 
   * @return array The resulting object representing created resource.
   */
  public function create(array $tag)
  {
    $attributes = array_intersect_key($tag, array_flip(self::$keysToPersist));
 
    list($code, $createdTag) = $this->httpClient->post("/tags", $attributes);
    return $createdTag; 
  }

  /**
   * Retrieve a single tag
   *
   * get '/tags/{id}'
   * 
   * Returns a single tag available to the user according to the unique ID provided
   * If the specified tag does not exist, this query will return an error
   *
   * @param integer $id Unique identifier of a Tag
   * 
   * @return array Searched Tag.
   */
  public function get($id)
  {
    list($code, $tag) = $this->httpClient->get("/tags/{$id}");
    return $tag;
  }
 
  /**
   * Update a tag
   *
   * put '/tags/{id}'
   * 
   * Updates a tag's information
   * If the specified tag does not exist, this query will return an error
   * **Notice** if you want to update a tag, you **must** make sure the tag's name is unique within the scope of the specified resource
   *
   * @param integer $id Unique identifier of a Tag
   * @param array $tag This array's attributes describe the object to be updated.
   * 
   * @return array The resulting object representing updated resource.
   */
  public function update($id, array $tag)
  {
    $attributes = array_intersect_key($tag, array_flip(self::$keysToPersist)); 
 
    list($code, $updatedTag) = $this->httpClient->put("/tags/{$id}", $attributes);
    return $updatedTag; 
  }

  /**
   * Delete a tag
   *
   * delete '/tags/{id}'
   * 
   * Deletes an existing tag
   * If the specified tag is assigned to any resource, we will remove this tag from all such resources
   * If the specified tag does not exist, this query will return an error
   * This operation cannot be undone
   *
   * @param integer $id Unique identifier of a Tag
   * 
   * @return boolean Status of the operation.
   */
  public function destroy($id)
  {
    list($code, $payload) = $this->httpClient->delete("/tags/{$id}");
    return $code == 204;
  }
}
