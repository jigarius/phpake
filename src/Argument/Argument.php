<?php

namespace Phpake\Argument;

/**
 * A standard argument.
 */
abstract class Argument implements ArgumentInterface {

  protected string $name;

  protected string $description;

  protected bool $isOptional;

  protected string|int|float|bool|NULL $defaultValue;

  protected function __construct(
    string $name,
    string $description = '',
    bool $isOptional = TRUE,
    $defaultValue = NULL
  ) {
    $this->name = $name;
    $this->description = $description;
    $this->isOptional = $isOptional;
    $this->defaultValue = $defaultValue;
  }

  final public static function create(
    string $name,
    string $description = '',
    bool $isOptional = TRUE,
    string|int|float|bool|NULL $defaultValue = NULL
  ): ArgumentInterface {
    return match ($name) {
      'command', 'input', 'output' => new BuiltInArgument($name),
      'rest' => new VariadicArgument($name, $description, $isOptional, $defaultValue),
      default => new RegularArgument($name, $description, $isOptional, $defaultValue),
    };
  }

  final public function getName(): string {
    return $this->name;
  }

  public function getDescription(): string {
    $description = $this->description ?? '';

    if ($this->isOptional) {
      $description = "[Optional] $description";
    }

    return $description;
  }

  final public function getIsOptional(): bool {
    return $this->isOptional;
  }

  final public function getDefaultValue(): string|int|float|bool|NULL {
    return $this->defaultValue;
  }

}
