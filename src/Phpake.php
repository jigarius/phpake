<?php

namespace Phpake;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Phpake\Commands\ExecCommand;

/**
 * A make-like built on PHP.
 */
class Phpake extends Application {

  /**
   * Application name.
   */
  const NAME = 'Phpake';

  /**
   * Application version.
   */
  const VERSION = '1.0.0';

  private ArgvInput $input;

  private ConsoleOutput $output;

  /**
   * Creates a Phpake Application instance.
   */
  public function __construct() {
    parent::__construct();
    $this->setName(self::NAME);
    $this->setVersion(self::VERSION);

    $this->input = new ArgvInput();
    $this->output = new ConsoleOutput();
    $this->configureIO($this->input, $this->output);
  }

  /**
   * Gets callbacks from a Phpakefile and adds them as commands.
   *
   * @param string $path
   *   Path to Phpakefile.
   */
  public function require(string $path) {
    $file = new PhpakeFile($path);
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
