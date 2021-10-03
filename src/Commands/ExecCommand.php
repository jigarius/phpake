<?php

namespace PHPake\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPake\Task;

class ExecCommand extends Command {

  private Task $task;

  protected function configure() {
    $task = new Task($this->getName());
    $this->setName($task->getCommand());
    $this->setDescription($task->getDescription());
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    call_user_func_array($this->callback, []);
    return 0;
  }

}