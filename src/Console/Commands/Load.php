<?php namespace EFrane\Tinkr\Console\Commands;

use SplObserver;
use SplSubject;

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
class Load extends Command implements SplSubject
{
  protected $observers = [];

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
        $this->notify();
      }
      catch (\Exception $e)
      {
        $this->getApplication()->renderException($e, $output);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function attach(SplObserver $observer)
  {
    $this->observers[get_class($observer)] = $observer;
  }

  /**
   * {@inheritdoc}
   */
  public function detach(SplObserver $observer)
  {
    unset($this->observers[get_class($observer)]);
  }

  /**
   * {@inheritdoc}
   */
  public function notify()
  {
    foreach ($this->observers as $observer)
    {
      /* @var \SplObserver $observer */
      $observer->update($this);
    }
  }
}