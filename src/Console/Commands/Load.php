<?php namespace EFrane\Tinkr\Console\Commands;

use Psy\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Load extends Command
{
  protected function configure()
  {
    $this
      ->setName('load')
      ->setDescription('Load a composer package into a tinkr environment');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeLn('Loading...');
  }

  protected function getPackages()
  {

  }
}