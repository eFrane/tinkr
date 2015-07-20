<?php namespace EFrane\Tinkr\Console;

use Symfony\Component\Console\Application;

class App extends Application
{
  public function __construct()
  {
    parent::__construct('tinkr', '0');

    $this->add(new Commands\Interactive);
    $this->setDefaultCommand('interactive');
  }
}

