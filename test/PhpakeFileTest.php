<?php

use Phpake\PhpakeFile;
use Phpake\PhpakeException;
use Phpake\TestCase;

/**
 * @covers \Phpake\PhpakeFile
 * @uses Phpake\TestCase
 */
class PhpakeFileTest extends TestCase {

  public function testInstanceWithInvalidFile() {
    $path = '/baa/baa/black/sheep/Phpakefile';
    $this->expectExceptionObject(
      new PhpakeException("File not found: $path")
    );
    new PhpakeFile($path);
  }

  public function testGetPath() {
    $path = static::createTempFile('<?php');
    $file = new PhpakeFile($path);
    $this->assertEquals($path, $file->getPath());
  }

  public function testGetCallbacksWithoutNamespace() {
    $func_1 = 'callback_' . uniqid();
    $func_2 = '_callback_' . uniqid();

    $path = static::createTempFile(
      <<<EOF
<?php

function $func_1() {  }
function $func_2() {  }

EOF
    );

    $file = new PhpakeFile($path);
    $this->assertEquals([$func_1, $func_2], $file->getCallbacks());
  }

  public function testGetCallbacksWithNamespace() {
    $namespace = 'namespace_' . uniqid();
    $func_1 = 'callback_' . uniqid();
    $func_2 = '_callback_' . uniqid();

    $path = static::createTempFile(
      <<<EOF
<?php

namespace $namespace;

function $func_1() {  }
function $func_2() {  }

EOF
    );

    $file = new PhpakeFile($path);
    $this->assertEquals(
      [$namespace . '\\' . $func_1, $namespace . '\\' . $func_2],
      $file->getCallbacks()
    );
  }

  /**
   * Two PhpakeFile instances with the same path can be created.
   */
  public function testRepeatInstance() {
    $func = 'callback_' . uniqid();

    $path = static::createTempFile(
      <<<EOF
<?php

function $func() {  }

EOF
    );

    $file = new PhpakeFile($path);
    $this->assertEquals([$func], $file->getCallbacks());

    $file = new PhpakeFile($path);
    $this->assertEquals([$func], $file->getCallbacks());
  }

  /**
   * PhpakeFile instance cannot be created if the file is already included.
   */
  public function testInstanceWithFileAlreadyIncluded() {
    $func = 'callback_' . uniqid();

    $path = static::createTempFile(
      <<<EOF
<?php

function $func() {  }

EOF
    );

    require $path;

    $this->assertTrue(function_exists($func));
    $this->expectExceptionObject(
      new PhpakeException("Phpakefile was already included: $path")
    );
    new PhpakeFile($path);
  }

  /**
   * PhpakeFile only cares about absolute, resolved paths.
   */
  public function testUsesResolvedPath() {
    $func = 'callback_' . uniqid();
    $path = static::createTempFile("<?php function $func() {}");
    $file = new PhpakeFile($path);

    $this->assertEquals([$func], $file->getCallbacks());

    $info = (object) pathinfo($path);
    $dirname = 'directory.' . uniqid();
    mkdir("$info->dirname/$dirname");
    $file = new PhpakeFile("$info->dirname/$dirname/../$info->basename");
    $this->assertEquals([$func], $file->getCallbacks());
  }

  public function testRequire() {
    $func = 'callback_' . uniqid();
    $path = static::createTempFile("<?php function $func() {}");
    PhpakeFile::require($path);

    $this->assertTrue(function_exists($func));

    // The second call doesn't raise an exception.
    PhpakeFile::require($path);
  }

  public function testDiscoverWithNoPhpakefile() {
    $old_cwd = getcwd();
    $new_cwd = sys_get_temp_dir() . '/' . uniqid();
    mkdir($new_cwd);
    chdir($new_cwd);

    $this->expectExceptionObject(
      new PhpakeException('Phpakefile not detected.')
    );
    PhpakeFile::discover();

    chdir($old_cwd);
  }

  public function testDiscoverWithPhpakefile() {
    $old_cwd = getcwd();
    $path = sys_get_temp_dir() . '/Phpakefile';
    file_put_contents($path, '<?php');
    chdir(dirname($path));

    $this->assertEquals($path, PhpakeFile::discover());

    chdir($old_cwd);
  }

}
