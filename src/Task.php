<?php

namespace Phpake;

use phpDocumentor\Reflection\DocBlockFactory;
use Phpake\Argument\Argument;

/**
 * A task, i.e. a command.
 */
class Task extends \stdClass {

  protected string $callback;

  protected \ReflectionFunction $reflection;

  protected array $arguments;

  /**
   * Creates a Phpake Task object for a callback.
   *
   * @param callable $callback
   *   A task callback, usually defined in a Phpakefile.
   * @throws \ReflectionException
   */
  public function __construct(callable $callback) {
    $this->callback = $callback;
    $this->reflection = new \ReflectionFunction($callback);

    $factory = DocBlockFactory::createInstance();
    if ($this->reflection->getDocComment() === FALSE) {
      $this->docblock = $factory->create("/**\n*/");
      return;
    }

    $this->docblock = $factory->create($this->reflection);
  }

  /**
   * Returns the name of the callback that defines and executes the task.
   *
   * @return string
   *   Function name.
   */
  public function getCallback(): string {
    return $this->callback;
  }

  /**
   * Returns a summary for the task.
   *
   * This is derived from the first line of the function's docblock.
   *
   * @return string
   *   A summary.
   */
  public function getSummary(): string {
    return $this->docblock->getSummary();
  }

  /**
   * Returns a description for the task.
   *
   * This is derived from the function's docblock, excluding the first line.
   *
   * @return string
   *   A description.
   */
  public function getDescription(): string {
    return $this->docblock->getDescription();
  }

  /**
   * Returns the name of the command associated to this task.
   *
   * @return string
   *   Command name.
   */
  public function getCommand(): string {
    return str_replace(
      ['_', '\\'],
      ['-', ':'],
      strtolower($this->callback)
    );
  }

  /**
   * Returns usage examples for the command.
   *
   * This is derived from usage tags in the function's docblock.
   *
   * @return array
   *   Usage examples.
   */
  public function getUsages(): array {
    return array_map('strval', $this->docblock->getTagsByName('usage'));
  }

  /**
   * Whether the task is exposed as a console command.
   *
   * If the callback name starts with an underscore, the command is hidden.
   *
   * @return bool
   *   True or False.
   */
  public function isHidden(): bool {
    return str_starts_with($this->reflection->getShortName(), '_');
  }

  /**
   * Get parameters for the callback associated to the task.
   *
   * @return \Phpake\Argument\ArgumentInterface[]
   *   Callback parameters.
   */
  public function getArguments(): array {
    if (isset($this->arguments)) {
      return $this->arguments;
    }

    $docblockParams = [];

    /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param $param */
    foreach ($this->docblock->getTagsByName('param') as $docblockParam) {
      $name = $docblockParam->getVariableName();
      $docblockParams[$name]['description'] = $docblockParam->getDescription()->render();
    }

    $result = [];
    foreach ($this->reflection->getParameters() as $reflParam) {
      $name = $reflParam->getName();
      $result[$name] = Argument::create(
        $name,
        isset($docblockParams[$name]) ? $docblockParams[$name]['description'] : '',
        $reflParam->isOptional(),
        $reflParam->isDefaultValueAvailable() ? $reflParam->getDefaultValue() : NULL
      );
    }

    return $this->arguments = $result;
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
   *   Exit code.
   */
  public function execute(array $args): int {
    $result = call_user_func_array($this->callback, $args);
    return is_int($result) ? $result : 0;
  }

}
