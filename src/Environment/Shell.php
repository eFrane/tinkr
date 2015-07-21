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

  public function __construct(Environment $env)
  {
    $shellConfig = $env->getPsyShConfiguration();
    $this->psy = new PsyShell($shellConfig);

    $this->psy->add(new Load);
    $this->psy->add(new Export);
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