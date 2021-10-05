<?php

namespace PHPake;

use phpDocumentor\Reflection\DocBlockFactory;

/**
 * A task, i.e. a command.
 */
class Task extends \stdClass {

  protected string $callback;

  protected \ReflectionFunction $reflection;

  public function __construct(callable $callback) {
    $this->callback = $callback;
    $this->reflection = new \ReflectionFunction($callback);

    $factory = DocBlockFactory::createInstance();
    $this->docblock = $factory->create($this->reflection);
  }

  public function getName(): string {
    return $this->callback;
  }

  public function getDescription(): string {
    return $comment = $this->docblock->getSummary();
  }

  public function getCommand(): string {
    return str_replace('_', '-', $this->callback);
  }

  /**
   * Get parameters for the callback associated to the task.
   *
   * @return \ReflectionParameter[]
   *   Callback parameters.
   */
  public function getParameters(): array {
    return $this->reflection->getParameters();
  }

  /**
   * Executes task callback.
   *
   * If the callback returns an integer, it is returned as is. Otherwise, a
   * zero is returned. This is then used as the application's exit code.
   *
   * @param array $args
   *   Callback arguments.
   *
   * @return int
   *   An exit code.
   */
  public function execute(array $args): int {
    $result = call_user_func_array($this->callback, $args);
    return is_int($result) ? $result : 0;
  }

}
