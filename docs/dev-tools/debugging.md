# Debugging for developers

!!! warning
    This is a work in progress.  Orange text needs updating.

When your code isn't doing what you want it to do, it's time to debug.
There are lots of options for debugging and there is lots you can do
without setting up a sophisticated debugging environment.  This chapter
contains some simple debugging tips and tricks to get you started and
also instructions on setting up XDebug, which is the recommended
debugging tool for CiviCRM when you have bugs which you are finding it
really hard to squish.

## UI options

These tools are activated by adding parameters to the URL that makes up
the page, e.g. `&backtrace=1`. Go to **Administer > System Settings >
Debugging and Error Handling** to enable these options, and find out
more about them.

#### **Enable Debugging**

!!! danger "Security Alert"
   Debug should **NOT** be enabled for production sites as it can result in system configuration and authentication information being exposed to unauthorized visitors.

CiviCRM has a debug mode which can be enabled to give you quick access
to a couple of useful diagnostic tools, including access to all the
smarty variables that make up a page. It also provides shortcut methods
to empty the file based cache and session variables.

Available debug commands are listed in the info box below.

**Debug URLs**
Debug output is triggered by adding specific name-value pairs to the CiviCRM query string **when Enable Debugging is set to Yes**:

-   Smarty Debug Window - Loads all variables available to the current page template into a pop-up window. To trigger, add `&smartyDebug=1` to any CiviCRM URL query string. Make sure you have pop-up blocking disabled in your browser for the CiviCRM site URL.
-   Session Reset - Resets all values in your client session. To trigger, add `&sessionReset=2`
-   Directory Cleanup - Empties template cache and/or temporary upload file folders.
   -   To empty template cache `civicrm/templates_c` folder), add `&directoryCleanup=1`
   -   To remove temporary upload files (`civicrm/upload` folder), add `&directoryCleanup=2`
   -   To cleanup both, add `&directoryCleanup=3`
-   Stack Trace - To display a stack trace listing at the top of a page, add `&backtrace=1`
-   Display Current Session - Displays the current users session variables in the browser, `&sessionDebug=1`


**Forcing debug output**

Sometimes using `&smartyDebug=1` to inspect variables available to a template will not work as expected.  An example of this is looking at the Contact Summary page using this method will display the variables available only to the summary tab and you might want to see the variables available to one of the other tabs.  To do this, after you have overridden the correct .tpl file, simply add  `{debug}` to any part of the file and the Smarty Debug Window will display all variables in the same scope as the `{debug}` statement.

#### Display Backtrace

!!! danger "Security Alert"
   Backtrace should **NOT** be enabled for production sites as it can result in information being exposed to unauthorized visitors.


The backtrace can be enabled independently of debugging. If this option
is selected, a backtrace will be displayed even if debugging is
disabled.

A backtrace is a list of all the functions that were run in the
execution of the page, and the php files that contain these functions.
It can be really useful in understanding the path that was taken through
code, what gets executed where, etc.

Set this to Yes if you want to display a backtrace listing when a fatal
error is encountered. This feature should **NOT** be enabled for
production sites.

You can also force the backtrace to be printed at any point in the code
by adding a call to `CRM_Core_Error::backtrace();`

## Statements to insert into you code

**Forcing debug output**

Sometimes using `&smartyDebug=1` to inspect variables available to a template will not work as expected.  An example of this is looking at the Contact Summary page using this method will display the variables available only to the summary tab and you might want to see the variables available to one of the other tabs.  To do this, after you have overridden the correct `.tpl` file, simply add  `{debug}` to any part of the file and the Smarty Debug Window will display all variables in the same scope as the `{debug}` statement.

You can also force the backtrace to be printed at any point in the code
by adding a call to `CRM_Core_Error::backtrace();`


### Settings which modify CiviCRM behavior for debugging

The following values can be added to your site's settings file
`civicrm.settings.php` to assist in debugging:

**Output all email to disk files OR discard**

`define('CIVICRM\_MAIL\_LOG', 1);`

This setting causes all outbound CiviCRM email to be written to files on your server's disk drive (Send Email to Contacts, Contribution and Event receipts, CiviMail mail ...). No real emails are sent. Emails are written to the `files/civicrm/ConfigAndLog/mail` (Drupal) or `media/civicrm/ConfigAndLog` directory (Joomla) by default.

`define('CIVICRM_MAIL_LOG', '/dev/null');`

This setting causes all outbound emails to be discarded. No email is sent and emails are NOT written to disk.

**Output all sql queries run by CiviCRM to a `CiviCRM.*.log` file**

Log files are located in the `files/civicrm/ConfigAndLog/` directory by default.

`define( 'CIVICRM_DEBUG_LOG_QUERY', 1 );`

`define( 'CIVICRM_DEBUG_LOG_QUERY', 'backtrace' );`

This setting will include a backtrace of the php functions that led to this query.

`define('CIVICRM_DAO_DEBUG', 1);`

Writes out various data layer queries to your browser screen.

In many cases enabling MySQL query logging can be more effective.

#### Printing PHP variables

`CRM_Core_Error::debug($name, $variable = null, $log = true, $html= true);`

can be called to print any variable. It is a wrapper around
`print_r($variable);` which does some clever stuff, but
`print_r($variable);` is often all you need.

Following your `print_r();` with and `exit;` to stop the script execution
at that point is often useful or necessary.

The `var_dump()` function gives similar output to `print_r()` but also
gives you information regarding data types and lengths, which can be
really useful during debugging.

## Log files

CiviCRM's log files are stored in the `civicrm/ConfigAndLog` directory
(below the `files` directory in Drupal sites, and below the `media`
directory in Joomla sites and under `wp-content/plugins/files/` directory
in Wordpress). Most runtime errors are logged here, as well as data that
you explicitly write to log using the `CRM_Core_Error::debug log=true`
parameter.

## Clearing the cache

Using Drupal, you can clear all caches with the following **drush**
command :

-   `drush cc civicrm`
-   `drush civicrm-cache-clear` *(older versions only)*

Alternatively, you can call the following two methods:

-   `CRM_Core_Config::clearDBCache();`
-   `CRM_Core_Config::cleanup();`

which clear the database and file cache respectively.

## Check the queries fired by Dataobject

    define( 'CIVICRM_DAO_DEBUG', 1 );

## Setting up a debugger and front end

## XDebug

XDebug is our main recommendation for developers that want to go
into hardcore debugging. Readers familiar with what a debugger is and
how it works should feel free to skip ahead to the "Setting Up XDebug"
section.

### What is a debugger?

A debugger is a software program that watches your code while it
executes and allows you to inspect, interrupt, and step through the
code. That means you can stop the execution right before a critical
juncture (for example, where something is crashing or producing bad
data) and look at the values of all the variables and objects to make
sure they are what you expect them to be. You can then step through the
execution one line at a time and see exactly where and why things break
down. It's no exaggeration to say that a debugger is a developer's best
friend. It will save you countless hours of beating your head against
your desk while you insert print statements everywhere to track down an
elusive bug.

Debugging in PHP is a bit tricky because your code is actually running
inside the PHP interpreter, which is itself (usually) running inside a
web server. This web server may or may not be on the same machine where
you're writing your code. If you're running your CiviCRM development
instance on a separate server, you need a debugger that can communicate
with you over the network. Luckily such a clever creature already
exists: XDebug.

### Setting Up XDebug

XDebug isn't the only PHP debugger, but it's the one we recommend for
CiviCRM debugging.

The instructions for downloading and installing XDebug are here:
[http://xdebug.org/docs/install](http://xdebug.org/docs/install)

Those instructions are a bit complex, however. There is a far simpler
way to install it if you happen to be running one of the operating
systems listed here.

#### Debian / Ubuntu Linux

    sudo apt-get install php5-xdebug

#### Red Hat / CentOS Linux

    sudo yum install php-pecl* php-devel php-pear
    sudo pecl install Xdebug

#### Mac OS X

    sudo port install php5-xdebug

#### Next Step for All Operating System

Tell XDebug to start automatically (don't do this on a production
server!) by adding the following two lines to your `php.ini` file (your
`php.ini` file is a php configuration file which is found somewhere on
your server.  Calling the `phpinfo()` function is probably the  easiest
way to tell you where this file is in your case.

    xdebug.remote_enable = On
    xdebug.remote_autostart = 1


Once XDebug is installed and enabled in your PHP configuration, you'll
need to restart your web server.

### Installing an XDebug Front-End

After you have XDebug running on your PHP web server, you need to
install a front-end to actually see what it is telling you about your
code. There are a few different options available depending on what
operating system you use:

#### All Operating Systems

NetBeans is a heavyweight Java IDE (Integrated Development Environment).
It offers lots of features, but isn't exactly small or fast. However, it
is very good at interactive debugging with XDebug. And since it's
written in  Java, it should run on any operating system you want to run
it on. You can find it at
[http://www.netbeans.org/](http://www.netbeans.org/)

After installing NetBeans, open your local CiviCRM installation in
NetBeans and click the Debug button on the toolbar. It will fire up your
web browser and start the debugger on CiviCRM. You may went to set a
breakpoint in `CRM/Core/Invoke.php` to make the debugger pause there. For
more information, see the NetBeans debugging documentation.

#### Mac OS X

A much lighter-weight option for Mac users is a program called MacGDBp.
You can download it here:
[http://www.bluestatic.org/software/macgdbp/](http://www.bluestatic.org/software/macgdbp/)

After installing MacGDBp, launch it and make sure it says "Connecting"
at the bottom in the status bar. If it doesn't, click the green "On"
button in the upper-right corner to enable it. The next time you access
CiviCRM, the web browser will appear to hang. If you click on MacGDBp,
you'll see that it has stopped on the first line of code in CiviCRM.
From there you can step through the code to the part you're interested
in. But it's probably a better idea to set a breakpoint in the part of
the code you're interested in stopping at. See the MacGDBp documentation
for more information.
