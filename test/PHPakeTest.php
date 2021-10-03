<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers PHPake
 */
class PHPakeTest extends TestCase {

  public function testName() {
    $app = new PHPake();
    $this->assertSame(PHPake::NAME, $app->getName());
  }

  public function testVersion() {
    $app = new PHPake();
    $this->assertMatchesRegularExpression('/^\d+\.\d+.\d+$/', $app->getVersion());
  }

}
