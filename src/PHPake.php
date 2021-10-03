<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * A make-like built on PHP.
 */
class PHPake extends Application {

  /**
   * @var Symfony\Component\Console\Input\ArgvInput
   */
  private ArgvInput $input;

  /**
   * @var Symfony\Component\Console\Output\ConsoleOutput
   */
  private ConsoleOutput $output;

  /**
   * Creates a PHPake Application instance.
   */
  public function __construct() {
    parent::__construct();
    $this->setName('PHPake');
    $this->setVersion('0.0.1');

    $this->input = new ArgvInput();
    $this->output = new ConsoleOutput();
    $this->configureIO($this->input, $this->output);

    $this->discoverTasks();
  }

  private function discoverTasks() {
    $phpakefile = $this->discoverPhpakefile();
    if (!$phpakefile) {
      exit(1);
    }

    // TODO: Parse Phakefile for tasks.
  }

  /**
   * @todo Add Windows compatibility?
   */
  private function discoverPhpakefile(): ?string {
    $lookup_path = getcwd();
    do {
      $path = $lookup_path . DIRECTORY_SEPARATOR . 'Phpakefile';
      if (is_file($path)) {
        $this->output->writeln("Phpakefile: $phpakefile", Output::VERBOSITY_VERBOSE);
        return $path;
      }

      $lookup_path = dirname($lookup_path);
    } while ($lookup_path !== DIRECTORY_SEPARATOR);

    $this->output->writeln("<error>[error]</error> Phpakefile not detected.");
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function run(InputInterface $input = NULL, OutputInterface $output = NULL): int {
    return parent::run($input ?? $this->input, $output ?? $this->output);
  }

}
