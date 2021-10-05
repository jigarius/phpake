<?php

namespace PHPake;

use http\Exception\RuntimeException;

/**
 * A task parameter.
 *
 * @example
 * phpake hello [name] # Name is a parameter.
 */
class Parameter extends \stdClass {

  protected string $callback;

  protected \ReflectionFunction $reflection;

  public function __construct(\ReflectionParameter $reflection) {
    $this->reflection = $reflection;
  }

  public function getName(): string {
    return $this->reflection->getName();
  }

  public function getDescription(): string {
    // TODO: Get description from DocBlock.
    return '';
  }

  public function getIsOptional() {
    return $this->reflection->isDefaultValueAvailable();
  }

  public function getDefaultValue() {
    return $this->reflection->getDefaultValue();
  }

}
