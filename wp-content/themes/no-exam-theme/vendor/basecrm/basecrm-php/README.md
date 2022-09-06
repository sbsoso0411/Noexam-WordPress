# basecrm-php

BaseCRM Official API V2 library client for PHP

## Installation

The recommended way to install the client is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version :

```bash
composer require basecrm/basecrm-php
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Usage

```php
require 'vendor/autoload.php';

// Then we instantiate a client (as shown below)
```

### Build a client
__Using this api without authentication gives an error__

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
```

### Client Options

The following options are available while instantiating a client:

 * __accessToken__: Personal access token
 * __baseUrl__: Base url for the api
 * __userAgent__: Default user-agent for all requests
 * __timeout__: Request timeout
 * __verbose__: Verbose/debug mode
 * __verifySSL__: Whether to verify SSL or not. Default: true

### Architecture

The library follows few architectural principles you should understand before digging deeper.
1. Interactions with resources are done via service objects.
2. Service objects are exposed as properties on client instances.
3. Service objects expose resource-oriented actions.
4. Actions return associative arrays.

For example, to interact with deals API you will use `\BaseCRM\DealsService`, which you can get if you call:

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
$client->deals; // \BaseCRM\DealsService
```

To retrieve list of resources and use filtering you will call `#all` method:

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
$client->deals->all(['organization_id' => google['id'], 'hot' => true]);
```

To find a resource by it's unique identifier use `#get` method:

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
$client->deals->get($id) # => array
```

When you'd like to create a resource, or update it's attributes you want to use either `#create` or `#update` methods. For example if you want to create a new deal you will call:

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
$deal = $client->deals->create(['name' => 'Website redesign', 'contact_id' => $id]);
```

To destroy a resource use `#destroy` method:

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
$client->deals->destroy($id) // => true
```

There other non-CRUD operations supported as well. Please contact corresponding service files for in-depth documentation.

### Full example

Create a new organization and after that change it's attributes (website).

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
$lead = $client->leads->create(['organization_name' => 'Design service company']);

$lead['website'] = "http://www.designservices.com"
$client->leads->update($lead['id'], $lead);
```

### Error handling

When you instantiate a client or make any request via service objects, exceptions can be raised for multiple
of reasons e.g. a network error, an authentication error, an invalid param error etc. 

Sample below shows how to properly handle exceptions:

```php
try
{
  // Instantiate a client.
  $client = new \BaseCRM\Client(['accessToken' => getenv('BASECRM_ACCESS_TOKEN')]);
  $lead = $client->leads->create(['organization_name' => 'Design service company']);

  print_r($lead);
}
catch (\BaseCRM\Errors\ConfigurationError $e)
{
  // Invalid client configuration option
}
catch (\BaseCRM\Errors\ResourceError $e)
{
  // Resource related error
  print('Http status = ' . $e->getHttpStatusCode() . "\n");
  print('Request ID = ' . $e->getRequestId() . "\n");
  foreach ($e->errors as $error)
  {
    print('field = ' . $error['field'] . "\n");
    print('code = ' . $error['code'] . "\n");
    print('message = ' . $error['message'] . "\n");
    print('details = ' . $error['details'] . "\n");
  }
}
catch (\BaseCRM\Errors\RequestError $e)
{
  // Invalid query parameters, authentication error etc.
}
catch (\BaseCRM\Errors\Connectionerror $e)
{
  // Network communication error, curl error is returned
  print('Errno = ' . $e->getErrno() . "\n");
  print('Error message = ' . $e->getErrorMessage() . "\n");
}
catch (Exception $e)
{
  // Other kind of exception
}
```

## Sync API

The following sample code shows how to perform a full synchronization flow using high-level wrapper.

First of all you need an instance of `\BaseCRM\Client`. High-level `\BaseCRM\Sync` wrapper uses `\BaseCRM\SyncService` to interact with the Sync API.
In addition to the client instance, you must provide a device’s UUID within `$deviceUUID` parameter. The device’s UUID must not change between synchronization sessions, otherwise the sync service will not recognize the device and will send all the data again.

```php
$client = new \BaseCRM\Client(['access_token' => '<YOUR_PERSONAL_ACCESS_TOKEN>']);
$sync = new \BaseCRM\Sync($client, '<YOUR_DEVICES_UUID');
```

Now all you have to do is to call `fetch` method and pass a block that you might use to store fetched data to a database.

```php
$sync->fetch(function ($meta, $data) {
  $options = [
    'table' => $meta['type'],
    'statement' => $meta['sync']['event_type'],
    'properties' => $data
  ];
  return \DAO::execute($options) ? \BaseCRM\Sync::ACK : \BaseCRM\Sync::NACK;
});
```

Notice that you must call either `#ack` or `#nack` method.

## Resources and actions

Documentation for every action can be found in corresponding service files under `lib/` directory.

### Account

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->accounts // => \BaseCRM\AccountsService
```

Actions:
* Retrieve account details - `client->accounts->self`

### AssociatedContact

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->associatedContacts // => \BaseCRM\AssociatedContactsService
```

Actions:
* Retrieve deal's associated contacts - `client->associatedContacts->all`
* Create an associated contact - `client->associatedContacts->create`
* Remove an associated contact - `client->associatedContacts->destroy`

### Contact

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->contacts // => \BaseCRM\ContactsService
```

Actions:
* Retrieve all contacts - `client->contacts->all`
* Create a contact - `client->contacts->create`
* Retrieve a single contact - `client->contacts->get`
* Update a contact - `client->contacts->update`
* Delete a contact - `client->contacts->destroy`

### Deal

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->deals // => \BaseCRM\DealsService
```

Actions:
* Retrieve all deals - `client->deals->all`
* Create a deal - `client->deals->create`
* Retrieve a single deal - `client->deals->get`
* Update a deal - `client->deals->update`
* Delete a deal - `client->deals->destroy`

**Note about deal value**

You can use either a string or numerical deal value when modifying a deal.
```php
$deal['value'] = 10;
$deal['value'] = 10.10;
$deal['value'] = "10.10";
```

### Lead

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->leads // => \BaseCRM\LeadsService
```

Actions:
* Retrieve all leads - `client->leads->all`
* Create a lead - `client->leads->create`
* Retrieve a single lead - `client->leads->get`
* Update a lead - `client->leads->update`
* Delete a lead - `client->leads->destroy`

### LossReason

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->lossReasons // => \BaseCRM\LossReasonsService
```

Actions:
* Retrieve all reasons - `client->lossReasons->all`
* Create a loss reason - `client->lossReasons->create`
* Retrieve a single reason - `client->lossReasons->get`
* Update a loss reason - `client->lossReasons->update`
* Delete a reason - `client->lossReasons->destroy`

### Note

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->notes // => \BaseCRM\NotesService
```

Actions:
* Retrieve all notes - `client->notes->all`
* Create a note - `client->notes->create`
* Retrieve a single note - `client->notes->get`
* Update a note - `client->notes->update`
* Delete a note - `client->notes->destroy`

### Pipeline

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->pipelines // => \BaseCRM\PipelinesService
```

Actions:
* Retrieve all pipelines - `client->pipelines->all`

### Source

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->sources // => \BaseCRM\SourcesService
```

Actions:
* Retrieve all sources - `client->sources->all`
* Create a source - `client->sources->create`
* Retrieve a single source - `client->sources->get`
* Update a source - `client->sources->update`
* Delete a source - `client->sources->destroy`

### Stage

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->stages // => \BaseCRM\StagesService
```

Actions:
* Retrieve all stages - `client->stages->all`

### Tag

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->tags // => \BaseCRM\TagsService
```

Actions:
* Retrieve all tags - `client->tags->all`
* Create a tag - `client->tags->create`
* Retrieve a single tag - `client->tags->get`
* Update a tag - `client->tags->update`
* Delete a tag - `client->tags->destroy`

### Task

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->tasks // => \BaseCRM\TasksService
```

Actions:
* Retrieve all tasks - `client->tasks->all`
* Create a task - `client->tasks->create`
* Retrieve a single task - `client->tasks->get`
* Update a task - `client->tasks->update`
* Delete a task - `client->tasks->destroy`

### User

```php
$client = new \BaseCRM\Client(['accessToken' => '<YOUR_PERSONAL_ACCESS_TOKEN>');
$client->users // => \BaseCRM\UsersService
```

Actions:
* Retrieve all users - `client->users->all`
* Retrieve a single user - `client->users->get`
* Retrieve an authenticating user - `client->users->self`


## Tests

Install PHPUnit via Composer:

```bash
$ composer install
```

To run all test suites:

```bash
$ BASECRM_ACCESS_TOKEN=<your-token-here> ./vendor/bin/phpunit
```

And to run a single suite:

```bash
$ BASECRM_ACCESS_TOKEN=<your-token-here> ./vendor/bin/phpunit --filter testUpdate tests/LeadsServiceTest.php 
```

## License
MIT

## Bug Reports
Report [here](https://github.com/basecrm/basecrm-php/issues).

## Contact
BaseCRM developers (developers@getbase.com)
