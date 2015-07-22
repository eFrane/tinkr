<?php namespace EFrane\Tinkr\Environment;

use Psy\Configuration;
use Symfony\Component\Filesystem\Filesystem;

class Environment
{
  protected $id        = '';
  protected $path      = '';
  protected $temporary = false;

  protected $fs = null;

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

    $this->fs = new Filesystem();

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

    $config->setHistoryFile($this->path . '/tinkr.history');
    $config->setHistorySize(0);

    $config->setDefaultIncludes([
      $_ENV['HOME'] . '/.composer/vendor/autoload.php',
      realpath('vendor/autoload.php')
    ]);

    $config->setDataDir('.');

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
    if (!is_dir($this->path))
      $this->fs->mkdir($this->path);

    if ($this->temporary)
    {
      // remove everything if the session is temporary
      register_shutdown_function(function () {
        $this->fs->remove($this->path);
      });
    }

    app()->instance('EFrane\Tinkr\Environment\Environment', $this);
    app()->alias('EFrane\Tinkr\Environment\Environment', 'env');

    app()->bind('composer', 'EFrane\Tinkr\Environment\Composer');
    app()->bind('shell', 'EFrane\Tinkr\Environment\Shell');
  }
}