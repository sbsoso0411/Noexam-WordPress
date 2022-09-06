<?php
namespace BaseCRM\Errors;

use Exception;

class ConnectionError extends Exception
{
  protected $errno, $error_message;

  public function __construct($errno, $error_message)
  {
    $this->errno = $errno;
    $this->error_message = $error_message;
    $msg = "Unexpected network error occurred during communication"
    . " with base api servers. Errno={$this->errno} Message={$this->error_message}.";

    parent::__construct($msg);
  }

  public function getErrno()
  {
    return $this->errno;
  }

  public function getErrorMessage()
  {
    return $this->error_message;
  }
}
