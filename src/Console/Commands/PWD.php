<?php namespace EFrane\Tinkr\Console\Commands;

use Psy\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PWD extends Command
{
  protected function configure()
  {
    $this->setName('pwd')->setDescription('Return the current working directory');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeln(getcwd());
  }
}