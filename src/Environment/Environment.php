<?php namespace EFrane\Tinkr\Environment;

use Psy\Configuration;
use Symfony\Component\Filesystem\Filesystem;

class Environment
{
  protected $path      = '';
  protected $temporary = false;

  public function __construct($path = '')
  {
    if (is_null($path))
    {
      $this->path = sys_get_temp_dir() .'/'. uniqid('tinkr_');
      $this->temporary = true;
    } else
    {
      $this->path = $path;
    }

    $this->setup();
  }

  public function getPath()
  {
    return $this->path;
  }

  public function isTemporary()
  {
    return $this->temporary;
  }

  /**
   * Get PsySh Configuration for the current environment
   *
   * @return Configuration
   */
  public function getPsyShConfiguration()
  {
    $config = new Configuration();

    $config->setHistoryFile($this->path . DIRECTORY_SEPARATOR . 'tinkr.history');

    return $config;
  }

  protected function setup()
  {
    if (!is_dir($this->path))
      mkdir($this->path, 0777, true);

    if ($this->temporary)
    {
      // remove everything if the session is temporary
      register_shutdown_function(function () {
        (new Filesystem())->remove($this->path);
      });
    }
  }
}