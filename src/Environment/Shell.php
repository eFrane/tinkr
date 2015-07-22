<?php namespace EFrane\Tinkr\Environment;

use EFrane\Tinkr\Console\Commands\Export;
use EFrane\Tinkr\Console\Commands\Load;
use Psy\Shell as PsyShell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

  public function run(InputInterface $input = null, OutputInterface $output = null)
  {
    return $this->psy->run($input, $output);
  }
}