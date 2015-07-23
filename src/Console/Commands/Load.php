<?php namespace EFrane\Tinkr\Console\Commands;

use Psy\Command\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Load Command - Load a package into the tinkr environment
 *
 * The load command integrates composer into the psysh
 * instance of the tinkr environment.
 *
 * It's as easy as `>>> load symfony/yaml` to integrate
 * a package into the testbed. This will automatically
 * `composer require` the package and reload the
 * shell to give access to the package.
 *
 * @package EFrane\Tinkr\Console\Commands
 **/
class Load extends Command
{
  protected function configure()
  {
    $this
      ->setName('load')
      ->setDescription('Load a composer package into a tinkr environment')
      ->addArgument(
        'packages',
        InputArgument::REQUIRED | InputArgument::IS_ARRAY,
        'A list of packages to be loaded. (Works basically like `composer require`)'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    /* @var \EFrane\Tinkr\Environment\Composer $composer */
    $composer = tinkr('composer');

    foreach ($input->getArgument('packages') as $package)
    {
      try
      {
        $composer->install($package);

        $output->writeln('<info>Resetting includes...</info>');

        /* @var \Psy\Shell $app */
        $app = $this->getApplication();

        $app->resetCodeBuffer();
        $app->addInput("require 'vendor/autoload.php'");
      }
      catch (\Exception $e)
      {
        $this->getApplication()->renderException($e, $output);
      }
    }
  }
}