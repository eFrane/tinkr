<?php namespace EFrane\Tinkr\Console\Commands;

use Psy\Command\Command;

use SplObserver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Load extends Command implements \SplSubject
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
      }
      catch (\Exception $e)
      {
        $this->getApplication()->renderException($e, $output);
      }
    }
  }

  public function attach(SplObserver $observer)
  {
    // TODO: Implement attach() method.
  }

  public function detach(SplObserver $observer)
  {
    // TODO: Implement detach() method.
  }

  public function notify()
  {
    // TODO: Implement notify() method.
  }
}