<?php

namespace AppBundle\Utils;

use Symfony\Component\Yaml;

class Book {

  /**
   * @return array
   */
  public static function pages() {
    $mkdocs = __DIR__ . '/../../../../mkdocs.yml';
    $navigation = (new Yaml\Parser())->parse(file_get_contents($mkdocs));

    // Need to do some funky array stuff to flatten out the structure here
    // because mkdocs.yml stores things in arrays without keys
    $pages = ArrayUtils::merge_inner_recursive($navigation['pages']);

    return $pages;
  }

}
