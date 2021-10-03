<?php

namespace PHPake\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecCommand extends Command {

  /**
   * A function name in a Phakefile that will handle this command.
   *
   * @var string
   */
  private string $callback;

  public function __construct(string $name = NULL) {
    $this->callback = $name;
    parent::__construct(str_replace('_', '-', $name));
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    call_user_func_array($this->callback, []);
    return 0;
  }

}