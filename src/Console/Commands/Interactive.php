<?php namespace EFrane\Tinkr\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psy\Shell;
use Psy\Configuration;

class Interactive extends Command
{
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
        'If set, the tinkr session will be persistently stored at the specified path'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $config = new Configuration();
    //$config->setHistoryFile();

    $shell = new Shell($config);

    $shell->run();
  }
}

