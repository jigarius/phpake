<?php

namespace Phpake;

/**
 * A file defining tasks.
 */
class PhpakeFile {

  /**
   * A /path/to/Phpakefile.
   *
   * @var string
   */
  protected string $path;

  /**
   * Callbacks contained in the Phpakefile.
   *
   * @var array
   */
  protected array $callbacks;

  static protected array $registry = [];

  public function __construct(string $path) {
    if (!is_file($path)) {
      throw new PhpakeException("File not found: $path");
    }

    $this->path = $path;
    $this->callbacks = $this->detectCallbacks();
  }

  public function getPath(): string {
    return $this->path;
  }

  public function getCallbacks(): array {
    return $this->callbacks;
  }

  /**
   * Detects callbacks from a Phpakefile.
   *
   * @return array
   *   Function names.
   */
  protected function detectCallbacks() {
    if (isset(self::$registry[$this->path])) {
      return self::$registry[$this->path];
    }

    if (in_array($this->path, get_included_files())) {
      // We use 'get_defined_functions()' for discovery. If the file has
      // already been included, we cannot detect the callbacks defined in
      // the file (unless we parse it ourselves). Thus, we resort to this
      // easy solution: one file can only be loaded once.
      throw new PhpakeException("Phpakefile was already included: $this->path");
    }

    $old_funcs = get_defined_functions()['user'];
    require_once $this->path;
    $new_funcs = get_defined_functions()['user'];

    return self::$registry[$this->path] = array_values(array_diff($new_funcs, $old_funcs));
  }

  /**
   * Discover a Phpakefile depending on the current working directory.
   *
   * @return string|null
   * @throws PhpakeException
   */
  public static function discover(): ?string {
    $path = getcwd();

    $candidate = $path . DIRECTORY_SEPARATOR . 'Phpakefile';
    if (is_file($candidate)) {
      return $candidate;
    }

    throw new PhpakeException('Phpakefile not detected.');
  }

}
