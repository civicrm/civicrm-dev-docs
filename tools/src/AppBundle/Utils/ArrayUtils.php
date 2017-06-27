<?php

namespace AppBundle\Utils;

class ArrayUtils {

  /**
   * If we have an array of arrays, we can use this function to merge all the
   * child arrays
   *
   * @param mixed $array
   *   Typically an array of arrays, but will accept anything.
   *
   *   Example:
   *   [
   *     0 => [
   *       'foo' => 'FOO',
   *       'bar' => 'BAR',
   *     ],
   *     1 => [
   *       'baz' => 'BAZ'
   *     ],
   *   ]
   *
   * @return mixed
   *   The array with elements merged. If a non-array is passed in, then it will
   *   be returned without error.
   *
   *   Output for above example:
   *   [
   *     'foo' => 'FOO',
   *     'bar' => 'BAR',
   *     'baz' => 'BAZ',
   *   ]
   */
  public static function merge_inner($array) {
    return is_array($array)
      ? call_user_func_array('array_merge', $array)
      : $array;
  }

  /**
   * Works like ArrayUtils::merge_inner but recursively.
   *
   * @param mixed $array
   *
   * @return mixed
   *
   */
  public static function merge_inner_recursive($array) {
    if (is_array($array)) {
      $result = self::merge_inner($array);
      $result = array_map('self::merge_inner_recursive', $result);
      return $result;
    }
    else {
      return $array;
    }
  }

}
