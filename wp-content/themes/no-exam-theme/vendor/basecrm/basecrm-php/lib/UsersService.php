<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\UsersService
 *
 * Class used to make actions related to User resource.
 * 
 * @package BaseCRM
 */
class UsersService
{
  protected $httpClient;

  /**
   * Instantiate a new UsersService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve all users
   *
   * get '/users'
   * 
   * Returns all users, according to the parameters provided
   *
   * @param array $options Search options
   * 
   * @return array The list of Users for the first page, unless otherwise specified.
   */
  public function all($options = [])
  {
    list($code, $users) = $this->httpClient->get("/users", $options);
    return $users;  
  }

  /**
   * Retrieve a single user
   *
   * get '/users/{id}'
   * 
   * Returns a single user according to the unique user ID provided
   * If the specified user does not exist, this query returns an error
   *
   * @param integer $id Unique identifier of a User
   * 
   * @return array Searched User.
   */
  public function get($id)
  {
    list($code, $user) = $this->httpClient->get("/users/{$id}");
    return $user;
  }

  /**
   * Retrieve an authenticating user
   *
   * get '/users/self'
   * 
   * Returns a single authenticating user, according to the authentication credentials provided
   *
   * 
   * @return array Resource object.
   */
  public function self()
  {
    list($code, $resource) = $this->httpClient->get("/users/self");
    return $resource;
  }
}
