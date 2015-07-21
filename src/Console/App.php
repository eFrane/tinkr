<?php namespace EFrane\Tinkr\Console;

use Symfony\Component\Console\Application;

class App extends Application
{
  const TINKR_VERSION = '0.1.0';

  public function __construct()
  {
    parent::__construct('tinkr', self::TINKR_VERSION);

    $this->add(new Commands\Interactive);
    $this->setDefaultCommand('interactive');
  }
}

