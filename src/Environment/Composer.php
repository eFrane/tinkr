<?php namespace EFrane\Tinkr\Environment;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class Composer
{
  protected $config = [];

  public function __construct(Environment $env)
  {
    $this->config = $env->getComposerConfiguration();
  }

  /**
   * @param string|array $query a string or array of package name search terms
   * @return array matching packages (might just be one)
   **/
  public function find($query)
  {
    if (is_string($query))
    {
      $query = [$query];
    }

    return array_map(function ($q) {
      ob_start();
      $this->runComposerCommand('search', $q);
      $output = ob_get_clean();

      var_export($output);

      return new ComposerResult($q, [$q]);
    }, $query);
  }

  /**
   * @param string $packageDescriptor A valid composer package descriptor (e.g. symfony/console, illuminate/config@dev, ...)
   * @return boolean installation success
   **/
  public function install($packageDescriptor)
  {
    $this->runComposerCommand('require', [$packageDescriptor]);
  }

  public function init(array $packages = [])
  {
    // make sure that we only init once
    if (file_exists($this->config['defaultArguments']['--working-dir'] . DIRECTORY_SEPARATOR . 'composer.json'))
      return;

    $needsInstall = false;

    $arguments = [
      '--name' => $this->config['packageName'],
      '--type' => 'project',
      '--author' => $this->config['author']
    ];

    if (count($packages) > 0)
    {
      $packages = array_map(function ($package) {
        if (strpos($package, ':') > 0)
        {
          return $package;
        } else
        {
          return $package . ':@stable';
        }
      }, $packages);

      $arguments = array_merge(
        ['--require' => $packages],
        $arguments
      );

      $needsInstall = true;
    }

    $this->runComposerCommand('init', $arguments);
    if ($needsInstall) $this->runComposerCommand('install');
  }

  /**
   * @param $commandName
   * @param array|string $arguments
   **/
  protected function runComposerCommand($commandName, $arguments = [])
  {
    if (is_string($arguments)) $arguments = [$arguments];

    $input = new ArrayInput(
      array_merge(
        ['command' => $commandName],
        $this->config['defaultArguments'],
        $arguments
      )
    );

    $composer = new Application();
    $composer->setAutoExit(false);
    $composer->run($input);
  }
}
