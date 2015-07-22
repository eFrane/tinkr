<?php namespace EFrane\Tinkr\Environment;

use EFrane\Tinkr\Console\Commands\Export;
use EFrane\Tinkr\Console\Commands\Load;
use Psy\Shell as PsyShell;

class Shell
{
  /**
   * @var PsyShell
   */
  protected $psy = null;
  protected $oldWorkingDirectory = '';

  public function __construct(Environment $env)
  {
    $this->oldWorkingDirectory = getcwd();
    chdir($env->getPath());

    $this->psy = new PsyShell($env->getPsyShConfiguration());

    $this->psy->add(new Load);
    $this->psy->add(new Export);
  }

  public function __destruct()
  {
    chdir($this->oldWorkingDirectory);
  }

  /**
   * @param Environment $env
   * @return Shell
   */
  public static function make(Environment $env)
  {
    return new self($env);
  }

  public function run()
  {
    return $this->psy->run();
  }
}