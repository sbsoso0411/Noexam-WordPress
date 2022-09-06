<?php
//  WARNING: This code is auto-generated from the BaseCRM API Discovery JSON Schema
namespace BaseCRM;

/**
 * BaseCRM\Coercion
 *
 * Class used to make coertion between data returned from Base and data usable for developer
 *
 * @package BaseCRM
 */

class Coercion
{
  /**
   * Coerce deal value to float with two decimal places
   *
   * @param $value Deal value
   */
  public static function toFloatValue($value = "0")
  {
    $newValue = round((float) $value, 2);
    return $newValue;
  }

  /**
   * Coerce deal value to float with two decimal places
   *
   *  @param $value Deal value
   */
  public static function toStringValue($value = 0)
  {
    $newValue = (string) $value;
    return $newValue;
  }
}

