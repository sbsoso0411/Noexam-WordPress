<?php
namespace BaseCRM\Errors;

use Exception;

class RateLimitError extends Exception
{
  public function __construct()
  {
    $msg = 'The api rate limit was exceeded. '
      . 'Contact Base Support (developers@getbase.com) in order to change the rate limits for your account';
    parent::__construct($msg);
  }
}
