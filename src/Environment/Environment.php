<?php namespace EFrane\Tinkr\Environment;

class Environment
{
  protected $path = '';

  public function __construct($path = '')
  {
    if (is_null($path))
    {
      $this->path = sys_get_temp_dir() .'/'. uniqid('tinkr_');
    } else
    {
      $this->path = $path;
    }

    $this->setup();
  }

  protected function setup()
  {
    if (!is_dir($this->path))
      mkdir($this->path, 0777, true);
  }

  public function getPath()
  {
    return $this->path;
  }
}