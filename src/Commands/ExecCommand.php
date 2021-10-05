<?php

namespace PHPake\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPake\Task;

/**
 * A command to execute Phpakefile tasks.
 *
 * This dynamic command that takes the name of a function as defined in a Phpakefile and
 * uses FunctionReflection to build an equivalent command for the CLI app.
 */
class ExecCommand extends Command {

  private Task $task;

  protected function configure() {
    $task = new Task($this->getName());
    $this->setName($task->getCommand());
    $this->setDescription($task->getDescription());

    foreach ($task->getArguments() as $argument) {
      $this->addArgument(
        $argument->name,
        $argument->isOptional ? InputArgument::OPTIONAL : InputArgument::REQUIRED,
        $argument->description,
        $argument->defaultValue,
      );
    }

    $this->task = $task;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $args = $input->getArguments();

    if (!array_key_exists('command', $args)) {
      unset($args['command']);
    }

    if (array_key_exists('input', $args)) {
      $args['input'] = $input;
    }

    if (array_key_exists('output', $args)) {
      $args['output'] = $output;
    }

    return $this->task->execute($args);
  }

}
