<?php

namespace Phpake\Argument;

/**
 * Symfony Console Command Argument.
 */
class BuiltInArgument extends Argument implements ArgumentInterface {

  protected function __construct(
    string $name,
    string $description = NULL,
    bool $isOptional = NULL,
    $defaultValue = NULL
  ) {
    if ($description || $isOptional || $defaultValue) {
      throw new \InvalidArgumentException('Built-in Argument only expects name.');
    }

    parent::__construct($name, '', TRUE, NULL);
  }

}
