<?php

namespace PHPake\Argument;

/**
 * Symfony Console Input Argument.
 */
class ConsoleInputArgument extends Argument implements ArgumentInterface {

  protected function __construct(
    string $name = NULL,
    string $description = NULL,
    bool $isOptional = TRUE,
    $defaultValue = NULL
  ) {
    parent::__construct('input', 'Symfony console input object.', TRUE, NULL);
    $this->isBuiltIn = TRUE;
  }

}
