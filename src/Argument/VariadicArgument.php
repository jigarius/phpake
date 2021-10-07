<?php

namespace Phpake\Argument;

/**
 * A multi-value, catch-all argument.
 */
class VariadicArgument extends Argument implements ArgumentInterface {

  protected function __construct(
    string $name,
    string $description,
    bool $isOptional,
    $defaultValue
  ) {
    if (!is_null($defaultValue)) {
      throw new \InvalidArgumentException('RestArgument default value must be NULL.');
    }

    parent::__construct(
      $name,
      $description ?: 'Rest of the arguments.',
      $isOptional = $isOptional,
      $defaultValue = $defaultValue
    );
  }

}
