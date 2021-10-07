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

  public function getName(): string {
    return $this->callback;
  }

  public function getDescription(): string {
    return $this->docblock->getSummary();
  }

  public function getCommand(): string {
    return str_replace(['_', '\\'], ['-', ':'], $this->callback);
  }

  /**
   * Whether the task is exposed as a console command.
   *
   * If the callback name starts with an underscore, the command is hidden.
   *
   * @return bool
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
   *   An exit code.
   */
  public function execute(array $args): int {
    $result = call_user_func_array($this->callback, $args);
    return is_int($result) ? $result : 0;
  }

}
