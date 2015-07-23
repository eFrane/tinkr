<?php namespace EFrane\Tinkr\Environment;

use EFrane\Tinkr\Console\Commands\Export;
use EFrane\Tinkr\Console\Commands\Load;
use EFrane\Tinkr\Console\Commands\PWD;
use Psy\Shell as PsyShell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class Shell
{
  /**
   * @var PsyShell
   */
  protected $psy = null;

  protected $env = null;

  protected $oldWorkingDir = '';

  public function __construct(Environment $env)
  {
    $this->oldWorkingDir = getcwd();
    chdir($env->getPath());

    $this->psy = new PsyShell($env->getPsyShConfiguration());

    $this->psy->setAutoExit(false);

    $this->psy->add(new Load);
    $this->psy->add(new Export);
    $this->psy->add(new PWD);

    $this->env = $env;
  }

  public function __destruct()
  {
    chdir($this->oldWorkingDir);
  }

  public function run(InputInterface $input = null, OutputInterface $output = null)
  {
    return $this->psy->run($input, $output);
  }
}