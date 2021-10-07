<?php

namespace Phpake;

/**
 * An argument defined by a task.
 *
 * @example
 * phpake hello [name] # Name is an argument.
 */
class Argument extends \stdClass {

  protected string $name;

  protected string $description;

  protected bool $isOptional;

  protected string|int|float|bool|NULL $defaultValue;

  public function __construct(
    string $name,
    string $description,
    bool $isOptional,
    string|int|float|bool|NULL $defaultValue
  ) {
    $this->name = $name;
    $this->description = $description;
    $this->isOptional = $isOptional;
    $this->defaultValue = $defaultValue;
  }

  public function __get(string $name) {
    return $this->$name;
  }

}
