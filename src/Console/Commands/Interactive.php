<?php namespace EFrane\Tinkr\Console\Commands;

use EFrane\Tinkr\Environment\Environment;
use EFrane\Tinkr\Environment\Shell;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Interactive extends Command
{
  /**
   * @var Environment
   **/
  protected $env = null;

  protected function configure()
  {
    $this
      ->setName('interactive')
      ->setDescription('Do the tinkering')
      ->addOption(
        'useCurrentDir', 
        null, 
        InputOption::VALUE_NONE, 
        'If set, the tinkr will run in the current working directory instead of a clean environment'
      )
      ->addOption(
        'saveTo',
        null,
        InputOption::VALUE_OPTIONAL,
        'If set, the tinkr session will be persistently stored at the specified path, this is mutually exclusive with --useCurrentDir'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->setupEnvironment($input);

    $message = ($this->env->isTemporary())
      ? 'Starting tinkr...'
      : 'Starting tinkr at `'.$this->env->getPath().'`...';

    $output->writeln('<info>'.$message.'</info>');

    Shell::make($this->env)->run();
  }

  /**
   * @param InputInterface $input
   **/
  protected function setupEnvironment(InputInterface $input)
  {
    if ($input->hasOption('saveTo')) {
      $this->env = new Environment($input->getOption('saveTo'));
    } else if ($input->hasOption('useCurrentDir')) {
      $this->env = new Environment(getcwd());
    } else {
      $this->env = new Environment();
    }
  }
}

