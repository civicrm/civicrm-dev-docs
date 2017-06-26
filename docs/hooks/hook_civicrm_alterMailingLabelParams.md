# hook_civicrm_alterMailingLabelParams

## Summary

This hook is called to alter the parameters used to generate mailing
labels.

## Availability

CiviCRM 4.1 or later

## Definition

    function hook_civicrm_alterMailingLabelParams( &$args )

## Parameters

-   $args: reference to the associative array of arguments that are
    about to be used to generate mailing labels
-

<!-- -->

    @param array $args an array of the args for the tcpdf MultiCell api call with the variable names below converted into string keys
      (ie $w become 'w' as the first key for $args).
      If ishtml is set true, then a subset of the args will be passed to the tcdpdf writeHTMLCell api call instead.

    float $w Width of cells. If 0, they extend up to the right margin of the page.
    float $h Cell minimum height. The cell extends automatically if needed.
    string $txt String to print
    mixed $border Indicates if borders must be drawn around the cell block. The value can
    be either
      a number:
        0: no border (default)
        1: frame or
      a string containing some or all of the following characters (in any order):
        L: left
        T: top
        R: right
        B: bottom
    string $align Allows to center or align the text. Possible values are:
      L or empty string: left align
      C: center
      R: right align
      J: justification (default value when $ishtml=false)
    int $fill Indicates if the cell background must be painted (1) or transparent (0). Default value: 0.
    int $ln Indicates where the current position should go after the call. Possible values are:
      0: to the right
      1: to the beginning of the next line DEFAULT
      2: below
    float $x x position in user units
    float $y y position in user units
    boolean $reseth if true reset the last cell height (default true).
    int $stretch stretch carachter mode:
      0 = disabled
      1 = horizontal scaling only if necessary
      2 = forced horizontal scaling
      3 = character spacing only if necessary
      4 = forced character spacing
    boolean $ishtml set to true if $txt is HTML content (default = false).
    boolean $autopadding if true, uses internal padding and automatically adjust it to account for line width.
    float $maxh maximum height. It should be >= $h and less then remaining space to the bottom of the page,
     or 0 for disable this feature. This feature works only when $ishtml=false.

NB: not all html tags are supported, not all style parameters are
supported, and improperly constructed html tends to lead to errors and
crashes.

## Returns

-   null

## Example

    function mymodule_civicrm_alterMailingLabelParams( &$args ) {
        $args['ishtml'] = true;
    }