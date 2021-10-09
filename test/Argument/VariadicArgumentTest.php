<?php

use Phpake\TestCase;
use Phpake\Argument\Argument;
use Phpake\Argument\VariadicArgument;

/**
 * @covers Phpake\Argument\VariadicArgument
 * @uses Phpake\Argument\Argument
 */
class VariadicArgumentTest extends TestCase {

  public function testInstance() {
    $arg = Argument::create('rest', '', TRUE, NULL);
    $this->assertInstanceOf(VariadicArgument::class, $arg);
  }

  public function testGetters() {
    $arg = Argument::create('rest', 'Rest of the arguments', FALSE, NULL);
    $this->assertEquals('rest', $arg->getName());
    $this->assertEquals('Rest of the arguments', $arg->getDescription());
    $this->assertFalse($arg->getIsOptional());
    $this->assertNull($arg->getDefaultValue());
  }

  public function testDefaultValueMustBeNull() {
    $this->expectException(InvalidArgumentException::class);
    Argument::create('rest', '', TRUE, 3);
  }

}
