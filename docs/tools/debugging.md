# Debugging

When your code isn't doing what you want it to do, it's time to debug. There are lots of options for debugging and there is lots you can do without setting up a sophisticated debugging environment.  This chapter contains some simple debugging tips and tricks to get you started and also instructions on setting up XDebug, which is the recommended debugging tool for CiviCRM when you have bugs which are really hard to squish.

!!! danger "Security Alert"
    None of these debugging methods should be performed on production sites, as they can expose system configuration and authentication information to unauthorized visitors.

The debugging methods presented here are ordered with the easiest ones first, but you may find the more challenging methods near the end to be more rewarding.

## Changing settings in the UI

CiviCRM has a debug mode which can be enabled via the UI to give you quick access to a couple of useful diagnostic tools, including all the Smarty variables that make up a page. It also provides shortcut methods to empty the file-based cache and session variables.

To use debugging via the UI, first go to **Administer > System Settings > Debugging and Error Handling** to enable these options, and find out more about them.

### Using URL parameters

After enabling debugging, append any of the following name-value pairs to the URL for the page you visit.

-   `&smartyDebug=1` opens the Smarty Debug Window which loads all variables available to the current page template into a pop-up window *(make sure you have pop-up blocking disabled)*.
-   `&sessionReset=2` resets all values in your client session.
-   `&directoryCleanup=1` empties template cache in `civicrm/templates_c`.
-   `&directoryCleanup=2` removes temporary upload files in `civicrm/upload`.
-   `&directoryCleanup=3` performs both of the above actions.
-   `&backtrace=1` displays a stack trace listing at the top of a page.
-   `&sessionDebug=1` displays the current users session variables in the browser.
-   `&angularDebug=1` displays live variable updates on certain Angular-based pages.

!!! tip "Caveats"
    - Sometimes using `&smartyDebug=1` to inspect variables available to a template will not work as expected. An example of this is looking at the Contact Summary page, when using this method will display the variables available only to the summary tab and you might want to see the variables available to one of the other tabs. To do this you will need to debug via code, as explained below.
    - If the page you are debugging does not already have a key-value parameter before debugging, you will need to begin the first parameter with a question mark instead of an ampersand.

### Displaying a backtrace

The backtrace can be enabled independently of debugging. If this option is selected, a backtrace will be displayed even if debugging is disabled. **A backtrace is normally displayed only when an error is encountered.** To manually trigger a backtrace in your custom code, see "Changing source code" (below).

A backtrace is a list of all the functions that were run in the execution of the page, and the PHP files that contain these functions. It can be really useful in understanding the path that was taken through code, what gets executed where, etc.

## Viewing log files

CiviCRM's log files are stored in the `ConfigAndLog` directory within CiviCRM's local file storage (see [the File System documentation](/framework/filesystem.md) for details on your CMS).  Most runtime errors are logged here, as well as data that you explicitly write to log using the `CRM_Core_Error::debug log=true` parameter.

One may also write to the log file without creating an error with:
`Civi::log()->debug(__FUNCTION__);`
This example will write the function-name to ConfigAndLog using the PHP constant, `__FUNCTION__`.

## Changing file-based settings

The following values can be added to your site's settings file `civicrm.settings.php` to assist in debugging:

- `define('CIVICRM_MAIL_LOG', 1);` causes all outbound CiviCRM email to be written to a log file. No real emails are sent.

- `define('CIVICRM_MAIL_LOG', '/dev/null');` causes all outbound emails to be discarded. No email is sent and emails are not written to disk.

- `define('CIVICRM_MAIL_LOG_AND_SEND', 1);` causes all outbound CiviCRM email to be written to a log file if `CIVICRM_MAIL_LOG` is set. Email will ALSO be sent using the configured method if this is set.

- `define('CIVICRM_DEBUG_LOG_QUERY', 1);` outputs all SQL queries to a log file.

- `define('CIVICRM_DEBUG_LOG_QUERY', 'backtrace');` will include a backtrace of the PHP functions that led to the query.

- `define('CIVICRM_DAO_DEBUG', 1);` writes out various data layer queries to your browser screen.

- `define('CIVICRM_CONTAINER_CACHE', 'never');` causes Civi to rebuild the [container](http://symfony.com/doc/current/service_container.html) from the latest data on every page-load.

- `define('CIVICRM_TEMP_FORCE_DURABLE', 1);` forces temp tables to be created as non temporary tables. Note that you may need to manually delete them very pro-actively while this is on as it will result in errors in some cases

!!! tip
    When any sort of "logging stuff to a file" is enabled by one of the
    above settings, check the `ConfigAndLog` directory within the local files
    directory to find the log.  (See [the File System
    documentation](/framework/filesystem.md) for the location in your CMS.)

!!! tip
    If you're developing within a site built with [civibuild](/tools/civibuild.md), then you can to apply settings to *all your local sites* by using the [civicrm.settings.d folders](/tools/civibuild.md#settings-civicrm).

## Viewing a query log from MySQL

Outside of CiviCRM, the [MySQL General Query Log](https://dev.mysql.com/doc/refman/en/query-log.html) log allows MySQL to log all queries. This is a performance killer, so avoid using it in production!

1. Inspect your current settings

    ```sql
    SHOW VARIABLES
    WHERE variable_name IN (
      'general_log',
      'general_log_file',
      'log_output'
      );
    ```

    ```text
    +------------------+------------------------------+
    | Variable_name    | Value                        |
    +------------------+------------------------------+
    | general_log      | OFF                          |
    | general_log_file | /var/lib/mysql/localhost.log |
    | log_output       | FILE                         |
    +------------------+------------------------------+
    ```

    You should see `general_log` set to `OFF` by default.

1. Turn on logging.

    ```sql
    SET GLOBAL general_log = 'ON';
    ```

    * To **log to a file**, leave `log_output` = `FILE`, as it is probably already set.

    * To **log to a table** (which can sometimes be easier to sift through), execute the following. Note that you need to leave `'TABLE'` as a string literal here

        ```sql
        SET GLOBAL log_output = 'TABLE';
        ```

1. Do something with CiviCRM that produces MySQL queries.

1. View the log.

    * If logging to a file, then find (or change) the location of the log file with the `general_log_file` variable.

    * If logging to a table run:

        ```sql
        SELECT * FROM mysql.general_log
        ```

1. (If logging to a table) clear the log. (It can be helpful to do this before performing an action that you'd like to inspect).

    ```sql
    TRUNCATE TABLE mysql.general_log;
    ```

1. When you're finished, turn *off* logging.

    ```sql
    SET GLOBAL general_log = 'OFF';
    ```

## Changing source code

### In Smarty template files

Add  `{debug}` to any part of the `.tpl` file and the Smarty Debug Window (described above) will display all variables in the same scope as the `{debug}` statement.

### Printing PHP variables

Show the contents of a variable:

```php
print_r($variable);
```

Show the contents of a variable, also with information regarding data types and lengths:

```php
var_dump($variable);
```

Another way to show the contents of a variable:

```php
CRM_Core_Error::debug($name, $variable = null, $log = true, $html= true);
```

Stop the script execution at that point.

```php
exit;
```

Print a backtrace:

```php
CRM_Core_Error::backtrace();
```

### In AngularJS HTML templates

```html
<div crm-ui-debug="myData"></div>
```

Then, be sure to add the parameter `angularDebug=1` to the URL.

## Clearing the cache

Clearing the cache is not a debugging technique, specifically. But sometimes it helps, and so is mentioned here for the sake of completeness.

Using a web browser:

-   Navigate directly to `civicrm/clearcache`

<!-- FIXME: This page is misleading:  Navigate to "Administer => System Settings => Cleanup Caches" -->

Using the command line, you can clear all caches with one of these commands:

-   `cv flush` (requires [`cv`](https://github.com/civicrm/cv))
-   `drush cc civicrm` (requires [`drush`](https://github.com/drush-ops/drush))

Alternatively, you can call the following methods in PHP code:

-   `civicrm_api3('System', 'flush', array());` clears many different caches
-   `CRM_Core_Config::clearDBCache();` clears *only* the database cache
-   `CRM_Core_Config::cleanup();` clears *only* the file cache

## Running a debugger program

### What is a debugger?

A debugger is a software program that watches your code while it executes and allows you to inspect, interrupt, and step through the code. That means you can stop the execution right before a critical juncture (for example, where something is crashing or producing bad data) and look at the values of all the variables and objects to make sure they are what you expect them to be. You can then step through the execution one line at a time and see exactly where and why things break down. It's no exaggeration to say that a debugger is a developer's best friend. It will save you countless hours of beating your head against your desk while you insert print statements everywhere to track down an elusive bug.

Debugging in PHP is a bit tricky because your code is actually running inside the PHP interpreter, which is itself (usually) running inside a web server. This web server may or may not be on the same machine where you're writing your code. If you're running your CiviCRM development instance on a separate server, you need a debugger that can communicate with you over the network. Luckily such a clever creature already exists: XDebug.

XDebug isn't the only PHP debugger, but it's the one we recommend for CiviCRM debugging.

### Installing XDebug

#### Debian / Ubuntu Linux (System Packages)

```bash
sudo apt-get install php5-xdebug
```

#### Red Hat / CentOS Linux (System Packages)

```bash
sudo yum install php-pecl* php-devel php-pear
sudo pecl install Xdebug
```

#### Mac OS X, Windows, and others

Generic instructions are provided with [XDebug's documentation](http://xdebug.org/docs/install).

Specific installation steps vary due to the diversity of PHP installation sources -- Apple's built-in PHP, brew, MAMP, XAMPP, AMPP, WAMP, Vagrant, Bitnami, and MacPorts are all a bit different. The best way to install XDebug is to identify your specific PHP runtime and then search Google, e.g. ["mamp xdebug"](https://www.google.com/#q=mamp+xdebug) or ["macports xdebug"](https://www.google.com/#q=macports+xdebug).

### Setting up PHP to talk to XDebug

Tell XDebug to start automatically (don't do this on a production server!) by adding the following two lines to your `php.ini` file (your `php.ini` file is a php configuration file which is found somewhere on your server.  Calling the `phpinfo()` function is probably the  easiest way to tell you where this file is in your case.

```php
xdebug.remote_enable = On
xdebug.remote_autostart = 1
```

Once XDebug is installed and enabled in your PHP configuration, you'll need to restart your web server.

### Installing an XDebug Front-End

After you have XDebug running on your PHP web server, you need to install a front-end to actually see what it is telling you about your code. There are a few different options available depending on what operating system you use.

#### NetBeans

[NetBeans](http://www.netbeans.org/) is a cross platform heavyweight Java IDE (Integrated Development Environment). It offers lots of features, but isn't exactly small or fast. However, it is very good at interactive debugging with XDebug. And since it's written in  Java, it should run on any operating system you want to run it on.

After installing NetBeans, open your local CiviCRM installation in NetBeans and click the Debug button on the toolbar. It will fire up your web browser and start the debugger on CiviCRM. You may went to set a breakpoint in `CRM/Core/Invoke.php` to make the debugger pause there. For more information, see the NetBeans debugging documentation.

#### MacGDBp

[MacGDBp](http://www.bluestatic.org/software/macgdbp/) is a lighter-weight option, only available for OS X. After installing MacGDBp, launch it and make sure it says "Connecting" at the bottom in the status bar. If it doesn't, click the green "On"
button in the upper-right corner to enable it. The next time you access CiviCRM, the web browser will appear to hang. If you click on MacGDBp, you'll see that it has stopped on the first line of code in CiviCRM. From there you can step through the code to the part you're interested in. But it's probably a better idea to set a breakpoint in the part of the code you're interested in stopping at. See the MacGDBp documentation for more information.

## Error 500, white screen (WSoD),  "Internal Server Error" errors

If CiviCRM shows a blank white page or page with "Error 500" with no other messages on screen, follow the steps in this section to diagnose and fix. A white screen (WSoD or "white screen of death") indicates that PHP is configured not to display errors, and has hit an error which it can't recover from. The result is an empty page.

Your next step is not to fix the error, but to first give yourself enough information to identify the source of the error. (Diagnose, then treat.)

**Viewing errors in logfiles**

The webserver can be configured to display errors to screen, but it also logs errors to files on disk. These files vary depending on your hosting environment, so you might consult your webhost's documentation to locate them. You might look for errors in some of these locations depending on webserver/php config:

-   `/var/log/nginx/*err*log` NginX webserver error logs
-   `/var/log/apache2/*err*log` Apache webserver & mod_php error logs
-   `/var/log/*php*log` PHP-FPM & PHP-CGI error logs
-   `/var/log/php5/*log` PHP-FPM & PHP-CGI error logs
-   `/path/to/site/err*log` Some hosting environments

*(The `*`s above represent a wildcard, not an actual filename. Eg the last entry might be public_html/error_log on Bluehost.)*

And a **CiviCRM specific debug log** file - location varies depending on hosting environment *and* CMS, refer to [this page](/tools/debugging.md#Debuggingfordevelopers-Logfiles) for location -

    path/to/site/path/to/civicrm/files/ConfigAndLog/CiviCRM*log

!!! Tip
    Buildkit uses [amp](https://github.com/amp-cli/amp) to install sites which means you have to look in `~/.amp`. Log files are in `~/.amp/log` and separated by site.

Once you've located these files, you can download them to view, or you can use tools like `tail` or `less +F` to follow the files. I prefer to follow logfiles because you can watch the error appear each time.

**Displaying errors to screen**

You may prefer to display errors to screen. This is probably disabled on your site because it's a security risk to some degree - an attacker can see more information when errors are visible, so the default configuration is often to restrict visibility to people with server access (via the logfiles above).

To enable error display, either:

**Configure your PHP to display errors for your site** via `php.ini` / `.htaccess` (see [How can I get PHP errors to display](http://stackoverflow.com/questions/1053424/how-do-i-get-php-errors-to-display)), OR

**Add PHP code to enable error display** (you can add it in `civicrm.settings.php` or the top of the `index.php` of your host CMS).

    <?php
      error_reporting(E_ALL);
      ini_set('display_errors', TRUE);
      ini_set('display_startup_errors', TRUE);

**Making sense of what you see**

Once you've taken one of the above approaches, try reproducing the actions which led to a white screen. If all's gone well, you should see an error now (on screen, or in your terminal / SSH session).

This is where you can start debugging meaningfully. There's a good chance you're exhausting server resources (timeouts, memory exhaustion) or hitting some coding error, but once you have the relevant error message at hand you'll be much better equipped to track down the source of the problem affecting your site.

**Further reading**

 * [Stack Overflow: How do I get PHP errors to display?](http://stackoverflow.com/questions/1053424/how-do-i-get-php-errors-to-display)
 * [CiviCRM StackExchange: Where should one look for logs when debugging a new problem](https://civicrm.stackexchange.com/questions/376/where-should-one-look-for-logs-when-debugging-a-new-problem)
 * [Drupal.org: Blank pages or White Screen of Death](https://www.drupal.org/node/158043)
 * [Joomla SE: What is an efficient way to troubleshoot a White Screen of Death](https://joomla.stackexchange.com/questions/299/what-is-an-efficient-way-to-troubleshoot-a-white-screen-of-death)

**Notes**

If this is the first time you've looked, there may be other errors visible which don't relate to the problem at hand. You may still need to discern what the actual problem is ...

If you're not familiar with UNIX, this may seem like a lot of effort. It's a lot less effort than *guessing* your way through a problem though!

## Debugging Javascript issues

### Symptoms
* Popup forms, tabs, custom fields, and other dynamic content fail to load or save correctly.
* Autocompletes or profile selectors look wrong (like a textbox that doesn't do anything) or fail to get results when you try to search.
* The CiviCRM menubar fails to appear at the top of the page.
* You frequently see error messages like "Network Error - Unable to reach the server."

### Background
Javascript (JS) is what makes web-apps interactive. Without it, nothing on a webpage can change without refreshing or clicking to a new page. The CiviCRM UI, especially in newer versions, uses javascript for everything from popup forms to date and time inputs. If a bug or misconfiguration on your website interferes with some aspect of JS, certain elements of the CiviCRM interface may function badly or not at all.

AJAX is a complicated-sounding acronym for the simple task of loading more stuff from the server on-demand. When you see a spinning triangle or have to wait for autocomplete results, the content is loading via AJAX. The J stands for Javascript, so if your site is having JS toubles, AJAX may not work either.

### Troubleshooting Tools
Most of the time the other debugging methods metioned earlier will solve 90% of the issues. However sometimes you need to use your browser to debug. Your web-browser (Firefox/Chrome/etc.) has a javascript console where you can view error messages. Press F12 to turn it on and then click over to the "Console" tab. With these tools enabled, try triggering the symptom again and watch for error messages on the screen or in the console. If you see an error message, try doing a google search for it to see if others have solved the same problem.

### Problems and Solutions
* Incompatible/buggy extensions for CiviCRM might crash javascript. Try temporarily turning them all off. If your problem is fixed, re-enable them one at a time to find the culprit.
* If your site is accessible from more than one url (e.g. `http://mysite.org` and `http://www.mysite.org`) AJAX requests may fail. Ask your host or sysadmin to restrict access to one url or the other, and ensure the one you choose matches `$civicrm_root` in `civicrm.settings.php`.
* Some people customize CiviCRM by overriding templates and php code. This custom code can become out-of-date and incompatible with the latest version, causing problems after upgrading. To see if your site has custom code, go to `Administer->System Settings->Directories` and check if either Custom Templates or Custom PHP Path Directory has been set (they are both blank by default). If so, try deleting them temporarily (don't forget to copy their values into a text file on your computer so you can restore them later). Check if your problem is solved, and if so, you can conclude that your custom code is in need up updating.
* On-screen php error messages can interfere with dynamic content loading from the server. Ensure the `display_errors` setting is off in your `php.ini` configuration.
* Certain website plugins have been known to cause javascript errors. Check this list of known incompatibilities.
* Ensure you are using the latest version of CiviCRM.
* Clear your site's caches.

If none of the above works:
Create a new post on Stack Exchange. Indicate that you have already read this and list what solutions you tried and what happened. List exact steps to reproduce the problem, and paste in the complete error message(s) you are seeing.

## Credits

Some content from this page was migrated from other sources
and contributed by the following authors:

* Chris Burgess
* Sean Madsen
