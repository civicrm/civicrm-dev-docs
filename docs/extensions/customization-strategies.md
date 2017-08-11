# Customization Strategies

Building a CiviCRM extension is the *recommended* way to customize CiviCRM, but others strategies exist too. This page summarizes some of the advantages and disadvantages of each strategy. 

| Capability | CiviCRM Extension | CiviCRM Custom PHP/Tpl Dir | Drupal Content (Smarty) | Drupal Module | Joomla Plugin (Civi v3.3+) | WordPress Plugin (Civi v4.6+) | 
| --- | --- | --- | --- | --- | --- | --- | 
| **Pre-Install** | Configure path; (For devs: install civix) | Configure path | Configure new format | None | None | None | 
| **Compatibility** | All CMSs | All CMSs | Drupal-only | Drupal-only | Joomla-only | WordPress-only | 
| **Distributability** | Good | Yes (Extra Steps) | Yes (Extra Steps) | Good | Good | Good | 
| **Packaging** | Yes | No | No | Yes | Yes | Yes | 
| **CiviCRM API** | Yes | Yes | Yes | Yes | Yes | Yes | 
| **CiviCRM Hooks** | Yes | No | No | Yes | Yes | Yes | 
| **CMS Hooks** | No | No | No | Yes | Yes | Yes | 
| **CodeGen** | Yes |  No |  No |  No |  No |  No | 
| **Reports** | Yes | Yes (Extra Steps) | No | Yes (Extra Steps) | Yes (Extra Steps) | Yes (Extra Steps) | 
| **Payment Processors** | Yes | Yes (Extra Steps) | No | Yes (Extra Steps) | Yes (Extra Steps) | Yes (Extra Steps) | 
| **Searches** | Yes | Yes (Extra Steps) | No | Yes (Extra Steps) | Yes (Extra Steps) | Yes (Extra Steps) | 
| **SQL Tables** | Yes (Extra Steps) | No | No | Yes | ? | ? | 
| **Web Page** | Yes | No | Yes | Yes |  Yes | Yes | 
| **Web Form** | Yes | No | No | Yes | Yes | Yes | 
| **Extend API** | Yes | Yes | No | Yes (Extra Steps) | Yes (Extra Steps) | Yes (Extra Steps) | 

Key: 

* **Pre-Install** - Are there any major steps one should take before using this type of add-on?
* **Compatibility** - Are there major constraints on which CiviCRM installations can use this?
* **Distributability** - How easy is it to share, mix, and match add-ons among different sites?
* **Packaging** - Does this require extra packaging steps (eg write info file, build tarball)?
* **CiviCRM API** - Can one CRUD entities with the API?
* **CiviCRM Hooks** - Can one respond to processing events in Civi?
* **CMS Hooks** - Can one respond to processing events in the CMS?
* **CodeGen** - Can one use the civix code generator?
* **Reports** - Can one package a new report?
* **Payment Processors** - Can one package a new payment processor?
* **Searches** - Can one package custom search screens?
* **SQL Tables** - Can one add/drop/migrate custom SQL tables?
* **Web Page** - Can one define new URLs with basic pages?
* **Web Form** - Can one define new URLS with forms?
* **Extend API** - Can one add entities or actions to the CiviCRM API?
