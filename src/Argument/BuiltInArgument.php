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
    parent::__construct($name, '', TRUE, NULL);
  }

  public function getDescription(): string {
    return '';
  }

}
