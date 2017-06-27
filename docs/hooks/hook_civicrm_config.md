# hook_civicrm_config

## Summary

This hook is called soon after the `CRM_Core_Config` object has been
initialized.

## Notes

You can use this hook to modify the config object and hence
behavior of CiviCRM dynamically.

## Definition

    hook_civicrm_config( &$config )

## Parameters

-   $config the config object

## Example

    function civitest_civicrm_config( &$config ) {
        $civitestRoot = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;

        // fix php include path
        $include_path = $civitestRoot . PATH_SEPARATOR . get_include_path( );
        set_include_path( $include_path );

        // fix template path
        $templateDir = $civitestRoot . 'templates' . DIRECTORY_SEPARATOR;
        $template =& CRM_Core_Smarty::singleton( );
        array_unshift( $template->template_dir, $templateDir );
    }