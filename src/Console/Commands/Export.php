<?php namespace EFrane\Tinkr\Console\Commands;

use Psy\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Export extends Command
{
  protected function configure()
  {
    $this
      ->setName('export')
      ->setDescription('Export a tinkr environment to a runnable project');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeLn('Exporting...');
  }
}