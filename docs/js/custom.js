
// Automatically scroll the navigation menu to the active element
//   https://github.com/civicrm/civicrm-dev-docs/issues/21
$('.wy-nav-side')
  .scrollTop(
    $('li.toctree-l1.current').offset().top -
    $('.wy-nav-side').offset().top -
    80
  );
