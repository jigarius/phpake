<?php

use Phpake\TestCase;
use Phpake\Argument\Argument;
use Phpake\Argument\RegularArgument;
use Phpake\Argument\BuiltInArgument;
use Phpake\Argument\VariadicArgument;

/**
 * @covers Phpake\Argument\Argument
 * @covers Phpake\Argument\RegularArgument
 * @uses Phpake\Argument\RegularArgument
 * @uses Phpake\Argument\BuiltInArgument
 * @uses Phpake\Argument\VariadicArgument
 */
class ArgumentTest extends TestCase {

  /**
   * All but the name can be assigned to built-in arguments.
   */
  public function testBuiltInArgument() {
    foreach (['command', 'input', 'output'] as $name) {
      $arg = Argument::create($name, 'A built-in argument', FALSE, 'foo');

      $this->assertInstanceOf(BuiltInArgument::class, $arg);
      $this->assertEquals($name, $arg->getName());
      $this->assertEquals('', $arg->getDescription());
      $this->assertTrue($arg->getIsOptional());
      $this->assertNull($arg->getDefaultValue());
    }
  }

  public function testRegularArgument() {
    foreach (['foo', 'bar', 'baz'] as $name) {
      $arg = Argument::create($name, 'A regular argument', FALSE, 'foo');

      $this->assertInstanceOf(RegularArgument::class, $arg);
      $this->assertEquals($name, $arg->getName());
      $this->assertEquals('A regular argument', $arg->getDescription());
      $this->assertFalse($arg->getIsOptional());
      $this->assertEquals('foo', $arg->getDefaultValue());
    }
  }

  public function testVariadicArgument() {
    $arg = Argument::create('rest', 'Everything else', FALSE, NULL);

    $this->assertInstanceOf(VariadicArgument::class, $arg);
    $this->assertEquals('rest', $arg->getName());
    $this->assertEquals('Everything else', $arg->getDescription());
    $this->assertFalse($arg->getIsOptional());
    $this->assertNull($arg->getDefaultValue());
  }

  /**
   * Variadic arguments can only have a default value of NULL.
   */
  public function testVariadicArgumentDefaultValue() {
    $this->expectException(InvalidArgumentException::class);
    Argument::create('rest', '', FALSE, 'foo');
  }

  public function getDescriptionRequired() {
    $arg = Argument::create('name', 'Your name', TRUE);
    $this->assertEquals(
      '[Optional] Your name',
      $arg->getDescription()
    );
  }

  public function getDescriptionOptional() {
    $arg = Argument::create('name', 'Your name', TRUE);
    $this->assertEquals(
      'Your name',
      $arg->getDescription()
    );
  }

  public function getDescriptionEmpty() {
    $arg = Argument::create('name', '', TRUE);
    $this->assertEquals(
      '[Optional]',
      $arg->getDescription()
    );
  }

}
