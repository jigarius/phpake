<?php

namespace PHPake;

/**
 * A file defining tasks.
 */
class PHPakeFile {

  /**
   * /path/to/Phpakefile
   *
   * @var string
   */
  protected string $path;

  public function __construct(string $path) {
    if (!is_file($path)) {
      throw new \RuntimeException("File not found: $path");
    }

    if (in_array($path, get_included_files())) {
      // We use 'get_defined_functions()' for discovery. If the file has
      // already been included, we cannot detect the callbacks defined in
      // the file (unless we parse it ourselves). Thus, we resort to this
      // easy solution: one file can only be loaded once.
      throw new RuntimeException('Task file already loaded.');
    }

    $this->path = $path;
  }

  public function getPath(): string {
    return $this->path;
  }

  public function getCallbacks(): array {
    $old_funcs = get_defined_functions()['user'];
    require_once $this->path;
    $new_funcs = get_defined_functions()['user'];

    return array_diff($new_funcs, $old_funcs);

    $task_funcs = array_diff($new_funcs, $old_funcs);
    return array_map(function($callable) {
      return new Task($callable);
    }, $task_funcs);
  }

  public static function discover(): ?string {
    $path = getcwd();
    do {
      $candidate = $path . DIRECTORY_SEPARATOR . 'Phpakefile';
      if (is_file($candidate)) {
        return $candidate;
      }

      $path = dirname($path);
    } while ($path !== DIRECTORY_SEPARATOR);

    throw new PHPakeException('Phpakefile not detected.');
  }

}
