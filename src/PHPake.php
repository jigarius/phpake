<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use PHPake\Commands\ExecCommand;
use PHPake\PHPakeFile;

/**
 * A make-like built on PHP.
 */
class PHPake extends Application {

  /**
   * Application name.
   */
  const NAME = 'PHPake';

  private ArgvInput $input;

  private ConsoleOutput $output;

  /**
   * Creates a PHPake Application instance.
   */
  public function __construct() {
    parent::__construct();
    $this->setName(self::NAME);
    $this->setVersion('0.0.1');

    $this->input = new ArgvInput();
    $this->output = new ConsoleOutput();
    $this->configureIO($this->input, $this->output);
  }

  public function require(string $path) {
    $file = new PHPakeFile($path);
    foreach ($file->getCallbacks() as $callback) {
      $command = new ExecCommand($callback);
      $this->add($command);
      $this->output->writeln("Added task: $callback()", ConsoleOutput::VERBOSITY_DEBUG);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function run(InputInterface $input = NULL, OutputInterface $output = NULL): int {
    return parent::run($input ?? $this->input, $output ?? $this->output);
  }

}
