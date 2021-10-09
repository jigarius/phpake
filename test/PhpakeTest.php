<?php

use Phpake\Phpake;
use Phpake\TestCase;

/**
 * @covers \Phpake\Phpake
 * @uses \Phpake\Phpakefile
 * @uses \Phpake\Task
 * @uses \Phpake\Commands\ExecCommand
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

  public function testRequire() {
    $app = new PHPake();

    $app->require(__DIR__ . '/../examples/hello-world.phpakefile');
    $this->assertCount(6, $app->all());
  }

}
