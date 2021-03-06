<?php

namespace Phpake\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Phpake\Argument\BuiltInArgument;
use Phpake\Task;

/**
 * A command to execute Phpakefile tasks.
 *
 * This dynamic command that takes the name of a function as defined in a Phpakefile and
 * uses FunctionReflection to build an equivalent command for the CLI app.
 */
class ExecCommand extends Command {

  private Task $task;

  protected function configure() {
    $task = new Task(new \ReflectionFunction($this->getName()));
    $this->setName($task->getCommand());
    $this->setHidden($task->isHidden());
    $this->setDescription($task->getSummary());
    $this->setHelp($task->getDescription());

    foreach ($task->getUsages() as $usage) {
      $this->addUsage($usage);
    }

    foreach ($task->getArguments() as $argument) {
      if (is_a($argument, BuiltInArgument::class)) {
        continue;
      }

      $mode = $argument->getIsOptional() ? InputArgument::OPTIONAL : InputArgument::REQUIRED;
      if ($argument->getName() === 'rest') {
        $mode = $mode | InputArgument::IS_ARRAY;
      }

      $this->addArgument(
        $argument->getName(),
        $mode,
        $argument->getDescription(),
        $argument->getDefaultValue(),
      );
    }

    $this->task = $task;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $values = $input->getArguments();
    $values['input'] = $input;
    $values['output'] = $output;

    $args = [];
    foreach ($this->task->getArguments() as $name => $argument) {
      $args[$name] = $values[$name];
    }

    return $this->task->execute($args);
  }

}
