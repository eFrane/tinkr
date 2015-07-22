<?php namespace EFrane\Tinkr\Environment;

use Psy\Configuration;
use Symfony\Component\Filesystem\Filesystem;

class Environment
{
  protected $id        = '';
  protected $path      = '';
  protected $temporary = false;

  public function __construct($path = '')
  {
    if (is_null($path))
    {
      $this->id = uniqid('tinkr_');

      $this->path = sys_get_temp_dir() .'/'. $this->id;
      $this->temporary = true;
    } else
    {
      $this->path = $path;

      $pathToId = explode(DIRECTORY_SEPARATOR, $path);
      $this->id = array_pop($pathToId);
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
    $config->setDefaultIncludes(['./vendor/autoload.php']);

    return $config;
  }

  public function getComposerConfiguration()
  {
    $authorName  = trim(shell_exec('git config user.name'));
    $authorEMail = trim(shell_exec('git config user.email'));

    return [
      'defaultArguments' =>
      [
        '--no-interaction' => true,
        '--working-dir' => $this->path
      ],
      'packageName' => $_ENV['USER'] . '/' . 'tinkr_' . $this->id,
      'author' => sprintf('%s <%s>', $authorName, $authorEMail)
    ];
  }

  protected function setup()
  {
    $fs = new Filesystem();

    if (!is_dir($this->path))
      $fs->mkdir($this->path);

    if ($this->temporary)
    {
      // remove everything if the session is temporary
      register_shutdown_function(function () use ($fs) {
        $fs->remove($this->path);
      });
    }

    app()->instance('EFrane\Tinkr\Environment\Environment', $this);
    app()->alias('EFrane\Tinkr\Environment\Environment', 'env');

    app()->bind('composer', 'EFrane\Tinkr\Environment\Composer');
    app()->bind('shell', 'EFrane\Tinkr\Environment\Shell');
  }
}