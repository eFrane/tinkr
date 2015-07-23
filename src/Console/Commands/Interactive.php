<?php namespace EFrane\Tinkr\Console\Commands;

use EFrane\Tinkr\Environment\Environment;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Interactive extends Command
{
  /**
   * @var Environment
   **/
  protected $env = null;

  protected $keepRunning = false;

  protected function configure()
  {
    $this
      ->setName('interactive')
      ->setDescription('Do the tinkering')
      ->addOption(
        'use-current-dir',
        null, 
        InputOption::VALUE_NONE, 
        'If set, the tinkr will run in the current working directory instead of a clean environment'
      )
      ->addOption(
        'path',
        'p',
        InputOption::VALUE_OPTIONAL,
        'If set, the tinkr session will be persistently stored at the specified path, this is mutually exclusive with --use-current-dir'
      )
      ->addArgument(
        'initPackages',
        InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
        'You may specify any number of packages to be loaded automatically on startup'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->setupEnvironment($input);

    $message = ($this->env->isTemporary())
      ? 'Preparing ' . tinkr_version() . '...'
      : 'Preparing ' . tinkr_version() . ' at `' . $this->env->getPath() . '`...';

    $output->writeln('<info>' . $message . '</info>');

    tinkr('composer')->init($input->getArgument('initPackages'));

    tinkr('shell')->run();

    if ($this->env->isTemporary())
      $output->writeln('<info>Cleaning up temporary tinkr environment...</info>');
  }

  /**
   * @param InputInterface $input
   **/
  protected function setupEnvironment(InputInterface $input)
  {
    if ($input->hasOption('path')) {
      $this->env = new Environment($input->getOption('path'));
    } else if ($input->hasOption('use-current-dir')) {
      $this->env = new Environment(getcwd());
    } else {
      $this->env = new Environment();
    }
  }
}

