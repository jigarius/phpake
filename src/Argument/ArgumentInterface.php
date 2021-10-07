<?php

namespace Phpake\Argument;

/**
 * An argument defined by a task.
 *
 * @example
 * phpake hello [name] # Name is an argument.
 */
interface ArgumentInterface {

  public function getName(): string;

  public function getDescription(): string;

  public function getIsOptional(): bool;

  public function getDefaultValue();

  public function getIsBuiltIn();

}
