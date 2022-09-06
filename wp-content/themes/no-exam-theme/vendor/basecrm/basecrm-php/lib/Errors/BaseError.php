<?php
namespace BaseCRM\Errors;

use Exception;

class BaseError extends Exception
{
  public $httpStatusCode, $errors, $meta;

  public function __construct($httpStatusCode, $response)
  {
    $this->httpStatusCode = $httpStatusCode;
    $this->errors = array_map(function($data){ return $data['error'];  }, $response['errors']);

    $this->meta = $response['meta'];
  
    $extractError = function($error) {
      return "resource=" . @$error['resource'] . " field=" . @$error['field'] . " code=" . $error['code'] . " message=" . $error['message'];
    };

    $msg = implode("\n", array_map($extractError, $this->errors));

    parent::__construct($msg);
  }

  public function getHttpStatusCode()
  {
    return $this->httpStatusCode;
  }

  public function getRequestId()
  {
    return $this->meta['logref'];
  }
}

