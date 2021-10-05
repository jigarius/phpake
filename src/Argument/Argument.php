<?php

namespace PHPake\Argument;

/**
 * A standard argument.
 */
class Argument implements ArgumentInterface {

  protected string $name;

  protected string $description;

  protected bool $isOptional;

  protected bool $isBuiltIn;

  protected string|int|float|bool|NULL $defaultValue;

  protected function __construct(
    string $name,
    string $description,
    bool $isOptional,
    $defaultValue
  ) {
    $this->name = $name;
    $this->description = $description;
    $this->isOptional = $isOptional;
    $this->defaultValue = $defaultValue;
    $this->isBuiltIn = FALSE;
  }

  final public static function create(
    string $name,
    string $description,
    bool $isOptional,
    $defaultValue
  ): ArgumentInterface {
    if ($name === 'command') {
      return new ConsoleCommandArgument($name);
    }

    if ($name === 'input') {
      return new ConsoleInputArgument($name);
    }

    if ($name === 'output') {
      return new ConsoleOutputArgument($name);
    }

    if ($name === 'rest') {
      return new RestArgument($name, $description, $isOptional, $defaultValue);
    }

    return new Argument($name, $description, $isOptional, $defaultValue);
  }

  final public function __get(string $name) {
    return match ($name) {
      'name' => $this->getName(),
      'description' => $this->getDescription(),
      'isOptional' => $this->getIsOptional(),
      'isBuiltIn' => $this->getIsBuiltIn(),
      'defaultValue' => $this->getDefaultValue(),
      default => throw new \InvalidArgumentException("Invalid property: $name"),
    };
  }

  public function getName(): string {
    return $this->name;
  }

  public function getDescription(): string {
    $description = $this->description;

    if ($this->isBuiltIn) {
      $description = "[Built-in] $description";
    }
    elseif ($this->isOptional) {
      $description = "[Optional] $description";
    }

    return $description;
  }

  public function getIsOptional(): bool {
    return $this->isOptional;
  }

  public function getIsBuiltIn(): bool {
    return $this->isBuiltIn;
  }

  public function getDefaultValue() {
    return $this->defaultValue;
  }

}
