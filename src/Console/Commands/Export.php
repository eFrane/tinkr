<?php namespace EFrane\Tinkr\Console\Commands;

use Psy\Command\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class Export extends Command
{
  protected function configure()
  {
    $this
      ->setName('export')
      ->setDescription('Export a tinkr environment to a runnable project')
      ->addOption(
        'force',
        'f',
        InputOption::VALUE_NONE,
        'Force overwriting if an exported version already exists'
      )
      ->addArgument(
        'path',
        InputArgument::OPTIONAL,
        'The path to where the tinkr session shall be exported'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    /* @var \EFrane\Tinkr\Environment\Environment $env */
    $env = tinkr('env');

    if (is_null($path = $input->getArgument('path')))
    {
      $path = $env->getCWD() . '/tinkr-session-' . $env->getSessionStart();
    }

    if (is_dir($path) && !$input->getOption('force'))
    {
      $output->writeln("<info>Export at {$path} already exists. Use --force to overwrite.</info>");
      return;
    }

    $output->writeLn("Exporting to {$path}...");

    $fs = new Filesystem();

    $fs->mkdir($path);
    $fs->mirror($env->getPath(), $path, null, [
      'delete' => $input->getOption('force'),
      'overwrite' => $input->getOption('force')
    ]);
  }
}