<?php namespace EFrane\Tinkr\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class App extends Application
{
  const TINKR_VERSION = '0.1.0';

  public function __construct()
  {
    parent::__construct('tinkr', self::TINKR_VERSION);
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

