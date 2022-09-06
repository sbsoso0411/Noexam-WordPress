<?php
namespace BaseCrm;

class CoercionTest extends \PHPUnit_Framework_TestCase
{
  public function testToFloatValue(){
    $this->assertEquals(Coercion::toFloatValue(0), 0);
    $this->assertEquals(Coercion::toFloatValue("0"), 0);
    $this->assertEquals(Coercion::toFloatValue(1.11), 1.11);
    $this->assertEquals(Coercion::toFloatValue("1.11"), 1.11);
  }

  public function testToStingValue(){
    $this->assertEquals(Coercion::toStringValue(10), "10");
    $this->assertEquals(Coercion::toStringValue(10.10), "10.1");
    $this->assertEquals(Coercion::toStringValue("10.10"), "10.10");
  }
}
