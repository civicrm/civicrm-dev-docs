<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\Book;

class HookListCommand extends Command {

  protected function configure() {
    $this
      ->setName('generate:hook-list')
      ->setDescription('Generates a summary listing page of all hooks');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $hooks = Book::pages()['Hooks'];

    //$output->write(var_export($hooks, TRUE));

  }

}
