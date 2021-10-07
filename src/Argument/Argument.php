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
    string $description,
    bool $isOptional,
    $defaultValue
  ) {
    $this->name = $name;
    $this->description = $description;
    $this->isOptional = $isOptional;
    $this->defaultValue = $defaultValue;
  }

  final public static function create(
    string $name,
    string $description,
    bool $isOptional,
    $defaultValue
  ): ArgumentInterface {
    return match ($name) {
      'command', 'input', 'output' => new BuiltInArgument($name),
      'rest' => new VariadicArgument($name, $description, $isOptional, $defaultValue),
      default => new RegularArgument($name, $description, $isOptional, $defaultValue),
    };
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

    if ($this->isOptional) {
      $description = "[Optional] $description";
    }

    return $description;
  }

  public function getIsOptional(): bool {
    return $this->isOptional;
  }

  public function getDefaultValue(): string|int|float|bool|NULL {
    return $this->defaultValue;
  }

}
