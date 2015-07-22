<?php namespace EFrane\Tinkr\Console\Commands;

use Psy\Command\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
    $composer = app('composer');

    $results = $composer->find($input->getArgument('packages'));

    foreach ($results as $result)
    {
      /* @var \EFrane\Tinkr\Environment\ComposerResult $result */
      if ($result->isAmbiguous())
      {
        $output->writeln("Found multiple results of {$result->getQuery()}, please select one:");
        $output->writeln($result->getResults());

        // TODO: Finish select dialog
        $solution = 0;
        $result->resolve($solution);
      }

    }
  }
}