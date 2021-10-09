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
   * Phpakefile callback registry.
   *
   * Callbacks in a Phpakefile are detected using get_defined_functions().
   * We compute a diff between the user-defined functions before and after
   * including a Phpakefile. Hence, we can only detect the callback names when
   * a Phpakefile is being included for the first time.
   *
   * If a new instance of PhpakeFile is created, it is impossible to detect
   * callback names (unless we parse the file ourselves). To work around this
   * problem, we track all callbacks in this registry. The keys of this assoc
   * are real paths to any loaded Phpakefile. The values are a list of
   * callbacks found within those files.
   *
   * @var array
   */
  protected static array $registry;

  public function __construct(string $path) {
    $this->path = realpath($path);
    self::require($path);
  }

  public function getPath(): string {
    return $this->path;
  }

  public function getCallbacks(): array {
    return self::$registry[$this->path];
  }

  /**
   * Detects callbacks from defined in a Phpakefile.
   *
   * @param string $path
   *   Path to a Phpakefile.
   *
   * @throws PhpakeException
   */
  public static function require(string $path) {
    $realpath = realpath($path);
    if ($realpath === FALSE) {
      throw new PhpakeException("File not found: $path");
    }

    if (isset(self::$registry[$realpath])) {
      return;
    }

    if (in_array($realpath, get_included_files())) {
      // We use 'get_defined_functions()' for discovery. If the file has
      // already been included, we cannot detect the callbacks defined in
      // the file (unless we parse it ourselves). Thus, we resort to this
      // easy solution: one file can only be loaded once.
      throw new PhpakeException("Phpakefile was already included: $path");
    }

    $old_funcs = get_defined_functions()['user'];
    require_once $realpath;
    $new_funcs = get_defined_functions()['user'];

    self::$registry[$realpath] = array_values(array_diff($new_funcs, $old_funcs));
  }

  /**
   * Discover a Phpakefile depending on the current working directory.
   *
   * @return string|null
   *   Path to a Phpakefile, if found.
   *
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
