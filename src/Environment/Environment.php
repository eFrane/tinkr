<?php namespace EFrane\Tinkr\Environment;

use Carbon\Carbon;
use Psy\Configuration;
use Symfony\Component\Filesystem\Filesystem;

class Environment
{
  protected $id        = '';
  protected $path      = '';
  protected $temporary = false;

  protected $cwd          = '';
  protected $sessionStart = null;

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

  public function getCWD()
  {
    return $this->cwd;
  }

  public function getSessionStart()
  {
    return $this->sessionStart->format('dmy-hi');
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

    $includes = [$_ENV['HOME'] . '/.composer/vendor/autoload.php'];

    if (file_exists($this->path . '/vendor/autoload.php'))
      $includes[] = realpath($this->path . '/vendor/autoload.php');

    $config->setDefaultIncludes($includes);

    $config->setDataDir(realpath($this->path));

    return $config;
  }

  public function getComposerConfiguration()
  {
    $authorName  = trim(shell_exec('git config user.name'));
    $authorEMail = trim(shell_exec('git config user.email'));

    return [
      'defaultParameters' =>
      [
        '--no-interaction',
        '--working-dir' => realpath($this->path),
      ],
      'packageName' => strtolower($_ENV['USER']) . '/' . $this->id,
      'author' => sprintf('%s <%s>', $authorName, $authorEMail)
    ];
  }

  protected function setup()
  {
    $this->cwd = getcwd();
    $this->sessionStart = Carbon::now();

    if (!is_dir($this->path))
      $this->fs->mkdir($this->path);

    if ($this->temporary)
    {
      // remove everything if the session is temporary
      register_shutdown_function(function () {
        $this->fs->remove($this->path);
      });
    }

    tinkr()->instance('EFrane\Tinkr\Environment\Environment', $this);
    tinkr()->alias('EFrane\Tinkr\Environment\Environment', 'env');

    tinkr()->bind('composer', 'EFrane\Tinkr\Environment\Composer');
    tinkr()->bind('shell', 'EFrane\Tinkr\Environment\Shell');
  }
}