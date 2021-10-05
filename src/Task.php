<?php

namespace PHPake;

/**
 * A task, i.e. a command.
 */
class Task extends \stdClass {

  protected string $callback;

  protected \ReflectionFunction $reflection;

  public function __construct(callable $callback) {
    $this->callback = $callback;
    $this->reflection = new \ReflectionFunction($callback);
  }

  public function getName() {
    $this->callback;
  }

  public function getDescription() {
    $comment = $this->reflection->getDocComment();
    $lines = preg_split("/\r\n|\n|\r/", $comment);

    array_shift($lines);
    array_pop($lines);

    foreach ($lines as &$line) {
      $line = preg_replace('@^(\s+\**\s*)@', '', $line);
    }

    return $lines[0] ?? '';
  }

  public function getCommand() {
    return str_replace('_', '-', $this->callback);
  }

  public function execute() {
    return call_user_func_array($this->callback, []);
  }

}
