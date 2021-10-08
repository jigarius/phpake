<?php

use Phpake\TestCase;

/**
 * @covers Phpake\TestCase
 */
class TestCaseTest extends TestCase {

  function testCreateTempFile() {
    $path = static::createTempFile('Bunny Wabbit');
    $this->assertFileExists($path);
    $this->assertEquals('Bunny Wabbit', file_get_contents($path));
  }

}
