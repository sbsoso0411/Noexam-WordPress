<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\TasksService
 *
 * Class used to make actions related to Task resource.
 * 
 * @package BaseCRM
 */
class TasksService
{
  // @var array Allowed attribute names.
  protected static $keysToPersist = ['completed', 'content', 'due_date', 'owner_id', 'remind_at', 'resource_id', 'resource_type'];

  protected $httpClient;

  /**
   * Instantiate a new TasksService instance.
   *
   * @param BaseCRM\HttpClient $httpClient Http client.
   */
  public function __construct(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve all tasks
   *
   * get '/tasks'
   * 
   * Returns all tasks available to the user, according to the parameters provided
   * If you ask for tasks without any parameter provided Base API will return you both **floating** and **related** tasks
   * Although you can narrow the search set to either of them via query parameters
   *
   * @param array $options Search options
   * 
   * @return array The list of Tasks for the first page, unless otherwise specified.
   */
  public function all($options = [])
  {
    list($code, $tasks) = $this->httpClient->get("/tasks", $options);
    return $tasks;  
  }

  /**
   * Create a task
   *
   * post '/tasks'
   * 
   * Creates a new task
   * You can create either a **floating** task or create a **related** task and associate it with one of the resource types below:
   * * [Leads](/docs/rest/reference/leads)
   * * [Contacts](/docs/rest/reference/contacts)
   * * [Deals](/docs/rest/reference/deals)
   *
   * @param array $task This array's attributes describe the object to be created.
   * 
   * @return array The resulting object representing created resource.
   */
  public function create(array $task)
  {
    $attributes = array_intersect_key($task, array_flip(self::$keysToPersist));
 
    list($code, $createdTask) = $this->httpClient->post("/tasks", $attributes);
    return $createdTask; 
  }

  /**
   * Retrieve a single task
   *
   * get '/tasks/{id}'
   * 
   * Returns a single task available to the user according to the unique task ID provided
   * If the specified task does not exist, this query will return an error
   *
   * @param integer $id Unique identifier of a Task
   * 
   * @return array Searched Task.
   */
  public function get($id)
  {
    list($code, $task) = $this->httpClient->get("/tasks/{$id}");
    return $task;
  }
 
  /**
   * Update a task
   *
   * put '/tasks/{id}'
   * 
   * Updates task information
   * If the specified task does not exist, this query will return an error
   *
   * @param integer $id Unique identifier of a Task
   * @param array $task This array's attributes describe the object to be updated.
   * 
   * @return array The resulting object representing updated resource.
   */
  public function update($id, array $task)
  {
    $attributes = array_intersect_key($task, array_flip(self::$keysToPersist)); 
 
    list($code, $updatedTask) = $this->httpClient->put("/tasks/{$id}", $attributes);
    return $updatedTask; 
  }

  /**
   * Delete a task
   *
   * delete '/tasks/{id}'
   * 
   * Delete an existing task
   * If the specified task does not exist, this query will return an error
   * This operation cannot be undone
   *
   * @param integer $id Unique identifier of a Task
   * 
   * @return boolean Status of the operation.
   */
  public function destroy($id)
  {
    list($code, $payload) = $this->httpClient->delete("/tasks/{$id}");
    return $code == 204;
  }
}
