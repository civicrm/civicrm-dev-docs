<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;
use AppBundle\Utils\Book;

class HookListCommand extends Command {

  /*
   * Data about all hooks, sorted by category
   */
  protected $categories = array();

  protected function configure() {
    $this
      ->setName('generate:hook-list')
      ->setDescription('Generates a summary listing page of all hooks');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->loadList();
    $this->loadHookSummaries();
    $this->writeOut();
  }

  /**
   * Populate $this->categories with data
   */
  protected function loadList() {
    // Get data from mkdocs.yml file under the 'Hooks' element
    $categories = Book::pages()['Hooks'];

    // Remove pages which are not about specific hooks
    $categories = array_map(function ($pages) {
      if (is_array($pages)) {
        return array_filter($pages, function ($page_url) {
          return preg_match('/hook_civicrm_/', $page_url);
        });
      }
      else {
        return FALSE;
      }
    }, $categories);

    // Remove empty categories
    $categories = array_filter($categories);

    $this->categories = $categories;
  }

  /**
   * Write the file with markdown content
   */
  protected function writeOut() {
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Resources/views');
    $twig = new Twig_Environment($loader);
    $content = htmlspecialchars_decode($twig->render(
      "hook_list.md.twig",
      array(
        'categories' => $this->categories,
        'command' => './bin/tools ' . $this->getName()
      )
    ));
    file_put_contents(__DIR__ . '/../../../../docs/hooks/list.md', $content);
  }
  
  protected function loadHookSummaries() {
    foreach ($this->categories as $category => $hooks) {

    }
  }

  protected function loadHookSummary($hookUrl) {

  }

}
