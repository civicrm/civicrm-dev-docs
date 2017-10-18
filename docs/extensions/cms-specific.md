# CMS-specific extensions development

When developing a native CiviCRM extension, sometimes you want to provide extra functionality for specific CMS platforms. This page provides instructions for doing so, where available.

## WordPress

### Shortcodes

Here's an example of a WordPress shortcode:

```text
[civicrm component="contribution" id="2" mode="live" discount="pkpwpabs7" hijack="0"]
```

Here's some code that an extension can use to receive data from the custom shortcode attribute and act on it:

```php
if ( function_exists( 'add_filter' ) ) {
	add_filter( 'civicrm_shortcode_preprocess_atts', 'extensionprefix_amend_args', 10, 2 );
	add_filter( 'civicrm_shortcode_get_data', 'extensionprefix_amend_data', 10, 3 );
}

/**
 * Filter the CiviCRM shortcode arguments.
 *
 * Modify the attributes that the 'civicrm' shortcode allows. The attributes
 * that are injected (and their values) will become available in the $_REQUEST
 * and $_GET arrays.
 *
 * @param array $args Existing shortcode arguments
 * @param array $shortcode_atts Shortcode attributes
 * @return array $args Modified shortcode arguments
 */
function extensionprefix_amend_args( $args, $shortcode_atts ) {

	// our custom attribute name & default
	$name = 'discount';
	$default = 'foo';

	// add either passed value or default
	if ( array_key_exists($name, $shortcode_atts) ) {
		$args[$name] = $shortcode_atts[$name];
	} else {
		$args[$name] = $default;
	}

	return $args;

}

/**
 * Filter the CiviCRM shortcode data array.
 *
 * Let's add some arbitrary text to the pre-rendered shortcode's description to
 * indicate that this extension has something to say.
 *
 * @param array $data Existing shortcode data
 * @param array $atts Shortcode attributes array
 * @param array $args Shortcode arguments array
 * @return array $data Modified shortcode data
 */
function extensionprefix_amend_data( $data, $atts, $args ) {

	// our custom attribute name
	$name = 'discount';

	// add some arbitrary text to pre-rendered shortcode
	if ( array_key_exists($name, $atts) ) {
		$data['text'] .= ' ' . ts('Discount code: ') . $atts[$name];
	}

	return $data;

}
```

In addition, more sophisticated plugins and extensions can also filter the parameters passed the the CiviCRM API when there are multiple shortcodes present. This would allow them to retrieve additional (or alternative) data for display in the pre-rendered shortcode.
