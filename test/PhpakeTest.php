<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers Phpake
 */
class PhpakeTest extends TestCase {

  public function testName() {
    $app = new Phpake();
    $this->assertSame(Phpake::NAME, $app->getName());
  }

  public function testVersion() {
    $app = new Phpake();
    $this->assertMatchesRegularExpression(
      '/^\d+\.\d+.\d+(-(alpha|beta|rc)\.\d+)?$/',
      $app->getVersion()
    );

    $json_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'composer.json';
    $json_data = json_decode(file_get_contents($json_path));

    $this->assertNotEmpty($json_data->version);
    $this->assertEquals($json_data->version, $app->getVersion());
  }

}
