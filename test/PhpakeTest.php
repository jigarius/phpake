<?php

use Composer\InstalledVersions;
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
    $this->assertEquals(
        InstalledVersions::getPrettyVersion('jigarius/phpake'),
        $app->getVersion()
    );
  }

  public function testRequire() {
    $app = new PHPake();

    $app->require(__DIR__ . '/../examples/hello-world.phpakefile');
    // 2 commands (help and list) are automatically added by Symfony Console.
    // 2 commands are hidden, i.e. have _ in the beginning.
    $this->assertCount(8, $app->all());
  }

}
