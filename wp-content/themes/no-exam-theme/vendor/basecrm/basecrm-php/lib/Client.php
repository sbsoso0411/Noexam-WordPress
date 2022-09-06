<?php
namespace BaseCRM;

/**
 * BaseCRM\Client
 * 
 * The client is the entry point to all services and actions.
 *
 * @package   BaseCRM
 * @author    BaseCRM developers <developers@getbase.com>
 */
class Client
{
  /**
   * @var \BaseCRM\HttpClient Http client
   * @ignore 
   */
  protected $httpClient;

  /**
   * @var \BaseCRM\Configuration Client configuratation
   */
  public $config;

  /**
   * @var \BaseCRM\AccountsService Access all Accounts related actions.
   * @see \BaseCRM\AccountsService
   * @see \BaseCRM\Account
   */
  public $accounts;

  /**
   * @var \BaseCRM\AssociatedContactsService Access all AssociatedContacts related actions.
   * @see \BaseCRM\AssociatedContactsService
   * @see \BaseCRM\AssociatedContact
   */
  public $associatedContacts;

  /**
   * @var \BaseCRM\ContactsService Access all Contacts related actions.
   * @see \BaseCRM\ContactsService
   * @see \BaseCRM\Contact
   */
  public $contacts;

  /**
   * @var \BaseCRM\DealsService Access all Deals related actions.
   * @see \BaseCRM\DealsService
   * @see \BaseCRM\Deal
   */
  public $deals;

  /**
   * @var \BaseCRM\LeadsService Access all Leads related actions.
   * @see \BaseCRM\LeadsService
   * @see \BaseCRM\Lead
   */
  public $leads;

  /**
   * @var \BaseCRM\LossReasonsService Access all LossReasons related actions.
   * @see \BaseCRM\LossReasonsService
   * @see \BaseCRM\LossReason
   */
  public $lossReasons;

  /**
   * @var \BaseCRM\NotesService Access all Notes related actions.
   * @see \BaseCRM\NotesService
   * @see \BaseCRM\Note
   */
  public $notes;

  /**
   * @var \BaseCRM\PipelinesService Access all Pipelines related actions.
   * @see \BaseCRM\PipelinesService
   * @see \BaseCRM\Pipeline
   */
  public $pipelines;

  /**
   * @var \BaseCRM\SourcesService Access all Sources related actions.
   * @see \BaseCRM\SourcesService
   * @see \BaseCRM\Source
   */
  public $sources;

  /**
   * @var \BaseCRM\StagesService Access all Stages related actions.
   * @see \BaseCRM\StagesService
   * @see \BaseCRM\Stage
   */
  public $stages;

  /**
   * @var \BaseCRM\TagsService Access all Tags related actions.
   * @see \BaseCRM\TagsService
   * @see \BaseCRM\Tag
   */
  public $tags;

  /**
   * @var \BaseCRM\TasksService Access all Tasks related actions.
   * @see \BaseCRM\TasksService
   * @see \BaseCRM\Task
   */
  public $tasks;

  /**
   * @var \BaseCRM\UsersService Access all Users related actions.
   * @see \BaseCRM\UsersService
   * @see \BaseCRM\User
   */
  public $users;

  /**
   * @var \BaseCRM\SyncService Access all Sync API related actions.
   * @see \BaseCRM\SyncService
   */
  public $sync;

  /*
   * Instantiate a new BaseCRM API V2 client. 
   * Client accepts an array of configuration options.
   * 
   * Here's an example of creating a client with an access token:
   *
   *  $client = new \BaseCRM\Client(["accessToken" => "YOUR_PERSONAL_ACCESS_TOKEN"]);
   * 
   * @param array $config Client configuration settings
   *    - accessToken: Personal access token
   *    - baseUrl: Base url for the api. Default: "https://api.getbase.com"
   *    - userAgent: Client user agent. Default: "BaseCRM/v2 PHP/{BaseCRM::VERSION}"
   *    - timeout: Request timeout. Default: 30 seconds
   *    - verifySSL: Whether to verify ssl or not. Default: true
   *    - verbose: Verbose/debug mode. Default: false
   *
   * @throws \BaseCRM\Errors\ConfigurationError if no access token provided
   * @throws \BaseCRM\Errors\ConfigurationError if provided access token is invalid - contains disallowed characters
   * @throws \BaseCRM\Errors\ConfigurationError if provided access token is invalid - has invalid length
   * @throws \BaseCRM\Errors\ConfigurationError if provided base url is invalid
   *
   */
  public function __construct(array $config = [])
  {
    $this->config = new Configuration($config);
    // raises ConfigurationError(s)
    $this->config->isValid();

    $this->httpClient = new HttpClient($this->config);

    $this->accounts = new AccountsService($this->httpClient);
    $this->associatedContacts = new AssociatedContactsService($this->httpClient);
    $this->contacts = new ContactsService($this->httpClient);
    $this->deals = new DealsService($this->httpClient);
    $this->leads = new LeadsService($this->httpClient);
    $this->lossReasons = new LossReasonsService($this->httpClient);
    $this->notes = new NotesService($this->httpClient);
    $this->pipelines = new PipelinesService($this->httpClient);
    $this->sources = new SourcesService($this->httpClient);
    $this->stages = new StagesService($this->httpClient);
    $this->tags = new TagsService($this->httpClient);
    $this->tasks = new TasksService($this->httpClient);
    $this->users = new UsersService($this->httpClient);

    $this->sync = new SyncService($this->httpClient);
  }
}
