<?php
namespace BaseCRM\Errors;

use Exception;

class ConfigurationError extends Exception
{
  public function __construct($message)
  {
    parent::__construct($message);
  }
}
