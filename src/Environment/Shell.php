<?php namespace EFrane\Tinkr\Environment;

use EFrane\Tinkr\Console\Commands\Export;
use EFrane\Tinkr\Console\Commands\Load;
use EFrane\Tinkr\Console\Commands\PWD;
use Psy\Shell as PsyShell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Shell
{
  /**
   * @var PsyShell
   */
  protected $psy = null;

  public function __construct(Environment $env)
  {
    $this->psy = new PsyShell($env->getPsyShConfiguration());

    $this->psy->add(new Load);
    $this->psy->add(new Export);
    $this->psy->add(new PWD);
  }

  public function run(InputInterface $input = null, OutputInterface $output = null)
  {
    return $this->psy->run($input, $output);
  }

  public function reset()
  {
    // TODO: implement shell reload without exiting the app
  }
}