<?php
namespace BaseCRM;

class PHPUnitUtil
{
  public static function getPrivateMethod($obj, $name) 
  {
    $class = new \ReflectionClass($obj);
    $method = $class->getMethod($name);
    $method->setAccessible(true);
    return $method;
  }

  public static function callMethod($obj, $name, array $args)
  {
    $method = self::getPrivateMethod($obj, $name);
    return $method->invokeArgs($obj, $args);
  }
}
