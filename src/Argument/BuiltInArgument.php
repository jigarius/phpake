<?php

namespace Phpake\Argument;

/**
 * Symfony Console Command Argument.
 */
class BuiltInArgument extends Argument implements ArgumentInterface {

  protected function __construct(
    string $name,
    string $description = '',
    bool $isOptional = TRUE,
    $defaultValue = NULL,
  ) {
    parent::__construct($name);
  }

  public function getDescription(): string {
    return '';
  }

}
