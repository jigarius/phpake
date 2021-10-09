<?php

namespace Phpake;

use PHPUnit\Framework\TestCase as TestCaseBase;

/**
 * Phpake Test Case.
 */
abstract class TestCase extends TestCaseBase {

  /**
   * Creates a temporary file with the given contents.
   *
   * @param string $data
   *   Contents to write to the file.
   *
   * @return string
   *   Path to the file.
   */
  protected static function createTempFile(string $data): string {
    $path = tempnam(sys_get_temp_dir(), 'Phpake.');
    file_put_contents($path, $data);
    return $path;
  }

}
