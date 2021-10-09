<?php

use Phpake\Task;
use Phpake\Argument\Argument;
use Phpake\TestCase;
use Phpake\PhpakeFile;

/**
 * @covers \Phpake\Task
 * @uses \Phpake\PhpakeFile
 * @uses \Phpake\Argument\Argument
 * @uses \Phpake\Argument\BuiltInArgument
 */
class TaskTest extends TestCase {

  protected Task $helloWorld;

  protected Task $helloLadies;

  protected Task $helloHuman;

  public function setUp(): void {
    // ReflectionFunction objects can only be created with real functions.
    // Loading these definitions with Phpake::require() makes sure that the
    // callbacks are identified properly.
    PhpakeFile::require(__DIR__ . '/../examples/hello-world.phpakefile');
    PhpakeFile::require(__DIR__ . '/../examples/input-output.phpakefile');

    // Has no annotations.
    $this->helloLadies = new Task(new ReflectionFunction('hello_ladies'));

    // Has just a summary.
    $this->helloWorld = new Task(new ReflectionFunction('hello_world'));

    // Has detailed annotations.
    $this->helloHuman = new Task(new ReflectionFunction('Input_Output\\hello_human'));
  }

  public function testGetCallback() {
    $this->assertEquals('hello_world', $this->helloWorld->getCallback());
  }

  public function testGetCallbackWithNamespace() {
    $this->assertEquals('Input_Output\\hello_human', $this->helloHuman->getCallback());
  }

  public function testGetSummary() {
    $this->assertEquals('Say hello to a human.', $this->helloHuman->getSummary());
  }

  public function testGetSummaryWithoutDocBlock() {
    $this->assertEquals('', $this->helloLadies->getSummary());
  }

  public function testGetDescription() {
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

  public function testGetDescriptionWithoutDocBlock() {
    $this->assertEquals('', $this->helloLadies->getDescription());
  }

  public function testGetDescriptionEmpty() {
    $this->assertEquals('', $this->helloWorld->getDescription());
  }

  public function testGetCommand() {
    $this->assertEquals('hello-world', $this->helloWorld->getCommand());
  }

  public function testGetCommandWithNamespace() {
    $this->assertEquals('input-output:hello-human', $this->helloHuman->getCommand());
  }

  public function testGetUsages() {
    $this->assertEquals(['Niki', 'Niki Martin'], $this->helloHuman->getUsages());
  }

  public function testGetUsagesEmpty() {
    $this->assertEquals([], $this->helloWorld->getUsages());
  }

  public function testIsHidden() {
    $this->assertFalse($this->helloWorld->isHidden());

    $task = new Task(new ReflectionFunction('_this_is_not_a_task'));
    $this->assertTrue($task->isHidden());
  }

  public function testIsHiddenWithNamespace() {
    $task = new Task(new ReflectionFunction('Input_Output\\_do_nothing'));
    $this->assertTrue($task->isHidden());
  }

  public function testGetArguments() {
    $this->assertEquals(
      [
        'fname' => Argument::create('fname', 'First name.', FALSE, NULL),
        'lname' => Argument::create('lname', 'Last name.', TRUE, NULL),
        'output' => Argument::create('output', '', TRUE, NULL),
      ],
      $this->helloHuman->getArguments()
    );
  }

  public function testGetArgumentsEmpty() {
    $this->assertEquals([], $this->helloWorld->getArguments());
  }

  /**
   * Task::getArguments() doesn't recompute argument data.
   */
  public function testGetArgumentsCachesResults() {
    $reflection = $this->getMockBuilder(\ReflectionFunction::class)
      ->setConstructorArgs(['Input_Output\\hello_human'])
      ->enableProxyingToOriginalMethods()
      ->getMock();

    $reflection->expects($this->exactly(1))
      ->method('getParameters');

    $task = new Task($reflection);
    $task->getArguments();
    // The second call gets the arguments from memory.
    $task->getArguments();
  }

  /**
   * If a callback returns a non-zero value, it is returned as is.
   *
   * @covers \Phpake\Task::execute
   */
  public function testExecuteReturnsNonZero() {
    $task = new Task(new ReflectionFunction('make_a_mess'));
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
