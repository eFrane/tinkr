<?php namespace EFrane\Tinkr\Console;

use Illuminate\Container\Container;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class App extends Application
{
  const TINKR_VERSION = '0.5.0';

  private static $container = null;

  public function __construct()
  {
    parent::__construct('tinkr', self::TINKR_VERSION);

    self::$container = new Container;
    self::container()->instance('app', $this);
  }

  public static function container()
  {
    return self::$container;
  }

  public static function tinkrVersion()
  {
    return 'tinkr v'.static::TINKR_VERSION;
  }

  public function getDefinition()
  {
    $inputDefinition = parent::getDefinition();
    $inputDefinition->setArguments();

    return $inputDefinition;
  }

  protected function getDefaultCommands()
  {
    $commands = parent::getDefaultCommands();

    $commands[] = new Commands\Interactive;

    return $commands;
  }

  protected function getCommandName(InputInterface $input)
  {
    return 'interactive';
  }
}

