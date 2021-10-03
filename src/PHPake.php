<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use PHPake\Commands\ExecCommand;

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

    $phpakefile = $this->discoverPhpakefile();
    if (!$phpakefile) {
      // TODO: Raise an exception instead.
      exit(1);
    }

    $this->require($phpakefile);
  }

  public function require(string $path) {
    if (!is_file($path)) {
      // TODO: Raise an exception instead.
      exit(1);
    }

    $old_funcs = get_defined_functions()['user'];
    require $path;
    $new_funcs = get_defined_functions()['user'];
    $task_funcs = array_diff($new_funcs, $old_funcs);

    if (!$task_funcs) {
      // TODO: Raise an exception instead.
      exit(1);
    }

    foreach ($task_funcs as $func) {
      $reflection = new ReflectionFunction($func);
      $command = new ExecCommand($reflection->getName());

      $this->add($command);
    }
  }

  /**
   * @todo Add Windows compatibility?
   */
  private function discoverPhpakefile(): ?string {
    $lookup_path = getcwd();
    do {
      $path = $lookup_path . DIRECTORY_SEPARATOR . 'Phpakefile';
      if (is_file($path)) {
        $this->output->writeln("Phpakefile: $path", ConsoleOutput::VERBOSITY_VERBOSE);
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
