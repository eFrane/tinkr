<?php namespace EFrane\Tinkr\Environment;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
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
    if (!is_string($packageDescriptor))
      throw new \InvalidArgumentException("Package descriptor must be a string");

    $this->runComposerCommand('require', [], $packageDescriptor);
  }

  public function init(array $packages = [])
  {
    // make sure that we only init once
    if (file_exists($this->config['defaultParameters']['--working-dir'] . '/composer.json'))
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
   * @param string $commandName
   * @param array|string $arguments
   * @param array|string $parameters
   **/
  protected function runComposerCommand($commandName, $parameters = [], $arguments = [])
  {
    $arguments = (array) $arguments;
    $parameters = (array) $parameters;

    $input = new ArrayInput(
      array_merge(
        ['command' => $commandName],
        $this->config['defaultParameters'],
        $parameters,
        $arguments
      )
    );

    // this is ugly but require doesn't seem to work otherwise

    if ($commandName === 'require')
    {
      exec('composer '.$input);
    } else
    {
      $composer = new Application();
      $composer->setAutoExit(false);
      $composer->run($input);
    }
  }
}
