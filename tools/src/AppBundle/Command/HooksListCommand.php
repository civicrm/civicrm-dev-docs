<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;
use AppBundle\Utils\Book;

class HooksListCommand extends Command {

  /*
   * Data about all hooks, sorted by category
   */
  protected $categories = array();

  protected function configure() {
    $this
      ->setName('generate:hooks-list')
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
    foreach ($categories as $category_name => &$pages) {
      if (is_array($pages)) {
        $pages = array_filter($pages, function ($page_url) {
          return preg_match('/hook_civicrm_/', $page_url);
        });
      }
      else {
        $pages = FALSE;
      }
    }

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
      "hooks_list.md.twig",
      array(
        'categories' => $this->categories,
        'command' => './bin/tools ' . $this->getName()
      )
    ));
    file_put_contents(__DIR__ . '/../../../../docs/hooks/list.md', $content);
  }

  /**
   * Load hook summary data into $this->categories for all hooks
   */
  protected function loadHookSummaries() {
    foreach ($this->categories as $category => &$hooks) {
      foreach ($hooks as $hook_name => &$value) {
        $value = array(
          'name' => $hook_name,
          'url' => $value,
          'summary' => self::lookupHookSummary($value),
        );
      }
    }
  }

  /**
   * Look up a hook summary in the markdown file for the hook and return it as
   * a string.
   *
   * It will grab all the content beneath the "Summary" H2 element and will
   * stop when it gets to the next heading
   *
   * @param string $hookUrl
   *   The URL for the hook as given in mkdocs.yml
   *   e.g. "hooks/hook_civicrm_links.md"
   *
   * @return string
   *   Short prose written to describe the hook. Won't contain any newlines.
   */
  protected static function lookupHookSummary($hookUrl) {
    // Load markdown file for the hook
    $hook = __DIR__ . '/../../../../docs/' . $hookUrl;
    $page = file_get_contents($hook);

    // Grab summary from within the page
    $matches = array();
    $pattern = '/(?<=^## Summary).*(?=^#)/Usim';
    preg_match($pattern, $page, $matches);
    $summary = !empty($matches[0]) ? $matches[0] : '';

    // Clean summary
    $summary = preg_replace('/\s+/', ' ', trim($summary));
    $summary = preg_replace('/This hook( is)?( was)? /', '', $summary);

    return $summary;
  }

}
