<?php

namespace Phpake\Argument;

/**
 * Symfony Console Output Argument.
 */
class ConsoleOutputArgument extends Argument implements ArgumentInterface {

  protected function __construct(
    string $name = NULL,
    string $description = NULL,
    bool $isOptional = TRUE,
    $defaultValue = NULL
  ) {
    parent::__construct('output', 'Symfony console output object.', TRUE, NULL);
    $this->isBuiltIn = TRUE;
  }

}
