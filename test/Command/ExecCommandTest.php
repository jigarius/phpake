<?php

use Phpake\TestCase;
use Phpake\PhpakeFile;
use Phpake\Task;
use Phpake\Commands\ExecCommand;

/**
 * @covers \Phpake\Commands\ExecCommand
 * @uses \Phpake\Task
 * @uses \Phpake\PhpakeFile
 * @uses \Phpake\Argument\Argument
 * @uses \Phpake\Argument\RegularArgument
 * @uses \Phpake\Argument\BuiltInArgument
 * @uses \Phpake\Argument\VariadicArgument
 */
class ExecCommandTest extends TestCase {

  protected Task $helloHuman;

  protected ExecCommand $command;

  public function setUp(): void {
    PhpakeFile::require(__DIR__ . '/../../examples/input-output.phpakefile');

    $callback = 'Input_Output\\hello_human';
    $this->helloHuman = new Task(new ReflectionFunction($callback));
    $this->command = new ExecCommand($callback);
  }

  public function testDescription() {
    $this->assertEquals('Say hello to a human.', $this->command->getDescription());
  }

  public function testHelp() {
    $this->assertEquals(
      <<<EOF
Uses a required parameter, and an optional parameter. "param" annotations
are used, to describe the parameters. These descriptions are displayed with
command-specific help.

Notice how the in-built parameter \$output was kept towards the end.
EOF,
      $this->command->getHelp()
    );
  }

  public function testUsages() {
    $this->assertEquals(
      ['input-output:hello-human Niki', 'input-output:hello-human Niki Martin'],
      $this->command->getUsages()
    );
  }

  public function testArguments() {
    $args = $this->command->getDefinition()->getArguments();

    $this->assertCount(2, $args);

    $fname = $args['fname'];
    $lname = $args['lname'];

    $this->assertEquals('fname', $fname->getName());
    $this->assertTrue($fname->isRequired());
    $this->assertEquals('First name.', $fname->getDescription());

    $this->assertEquals('lname', $lname->getName());
    $this->assertFalse($lname->isRequired());
    $this->assertEquals('[Optional] Last name.', $lname->getDescription());
  }

  public function testRestArgument() {
    PhpakeFile::require(__DIR__ . '/../../examples/variadic.phpakefile');

    $callback = 'flight_path';
    $task = new Task(new ReflectionFunction($callback));
    $command = new ExecCommand($callback);

    $rest = $command->getDefinition()->getArgument('rest');
    $this->assertTrue($rest->isArray());
    $this->assertTrue($rest->isRequired());
    $this->assertEquals('Other destinations.', $rest->getDescription());
    $this->assertIsArray($rest->getDefault());
  }

}
