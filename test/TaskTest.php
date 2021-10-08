<?php

use PHPUnit\Framework\TestCase;
use Phpake\Task;
use Phpake\Argument\Argument;

require_once __DIR__ . '/../examples/hello-world.phpakefile';
require_once __DIR__ . '/../examples/input-output.phpakefile';
require_once __DIR__ . '/../examples/fizzbuzz.phpakefile';

/**
 * @covers \Phpake\Task
 * @uses \Phpake\Argument\Argument
 * @uses \Phpake\Argument\BuiltInArgument
 */
class TaskTest extends TestCase {

  protected Task $helloWorld;

  protected Task $helloLadies;

  protected Task $helloHuman;

  public function setUp(): void {
    $this->helloLadies = new Task('hello_ladies');
    $this->helloWorld = new Task('hello_world');
    $this->helloHuman = new Task('Input_Output\\hello_human');
  }

  public function testGetCallback() {
    $this->assertEquals('hello_world', $this->helloWorld->getCallback());
    $this->assertEquals('Input_Output\\hello_human', $this->helloHuman->getCallback());
  }

  public function testGetSummary() {
    $this->assertEquals('', $this->helloLadies->getSummary());
    $this->assertEquals('Say hello world.', $this->helloWorld->getSummary());
    $this->assertEquals('Say hello to a human.', $this->helloHuman->getSummary());
  }

  public function testGetDescription() {
    $this->assertEquals('', $this->helloLadies->getDescription());
    $this->assertEquals('', $this->helloWorld->getDescription());
    $this->assertEquals(
      <<<EOD
Uses a required parameter, and an optional parameter. "param" annotations
are used, to describe the parameters. These descriptions are displayed with
command-specific help.

Notice how the in-built parameter \$output was kept towards the end.
EOD,
      $this->helloHuman->getDescription()
    );
  }

  public function testGetCommand() {
    $this->assertEquals('hello-world', $this->helloWorld->getCommand());
    $this->assertEquals('input-output:hello-human', $this->helloHuman->getCommand());
  }

  public function testGetUsages() {
    $this->assertEquals([], $this->helloWorld->getUsages());
    $this->assertEquals(['Niki', 'Niki Martin'], $this->helloHuman->getUsages());
  }

  public function testIsHidden() {
    $this->assertFalse($this->helloWorld->isHidden());

    $task = new Task('_this_is_not_a_task');
    $this->assertTrue($task->isHidden());

    $task = new Task('Fizzbuzz\\_fizzbuzz');
    $this->assertTrue($task->isHidden());
  }

  public function testGetArguments() {
    $this->assertEquals([], $this->helloWorld->getArguments());
    $this->assertEquals([
      'fname' => Argument::create('fname', 'First name.', FALSE, NULL),
      'lname' => Argument::create('lname', 'Last name.', TRUE, NULL),
      'output' => Argument::create('output', '', TRUE, NULL),
    ],
    $this->helloHuman->getArguments());
  }

  /**
   * If a callback returns a non-zero value, it is returned as is.
   *
   * @covers \Phpake\Task::execute
   */
  public function testExecuteReturnsNonZero() {
    $task = new Task('make_a_mess');
    $this->assertEquals(19, $task->execute([]));
  }

  /**
   * If a callback returns nothing, execute() returns zero.
   *
   * @covers \Phpake\Task::execute
   */
  public function testExecuteReturnsZero() {
    $this->expectOutputString('Hello world' . PHP_EOL);
    $this->assertEquals(0, $this->helloWorld->execute([]));
  }

}
