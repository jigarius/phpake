<?php

namespace PHPake\Argument;

/**
 * Symfony Console Command Argument.
 */
class ConsoleCommandArgument extends Argument implements ArgumentInterface {

  protected function __construct(
    string $name = NULL,
    string $description = NULL,
    bool $isOptional = NULL,
    $defaultValue = NULL
  ) {
    parent::__construct('command', 'Command name.', TRUE, NULL);
    $this->isBuiltIn = TRUE;
  }

}
