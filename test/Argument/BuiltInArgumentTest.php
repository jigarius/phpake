<?php

use Phpake\TestCase;
use Phpake\Argument\Argument;
use Phpake\Argument\BuiltInArgument;

/**
 * @covers Phpake\Argument\BuiltInArgument
 * @uses Phpake\Argument\Argument
 */
class BuiltInArgumentTest extends TestCase {

  public function testInstance() {
    $arg = Argument::create('command', '', TRUE, NULL);
    $this->assertInstanceOf(BuiltInArgument::class, $arg);
  }

  public function testGetters() {
    $arg = Argument::create('command', '', TRUE, NULL);
    $this->assertEquals('command', $arg->getName());
    $this->assertEquals('', $arg->getDescription());
    $this->assertTrue($arg->getIsOptional());
    $this->assertNull($arg->getDefaultValue());
  }

}
