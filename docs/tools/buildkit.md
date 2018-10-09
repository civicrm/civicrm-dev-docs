# Buildkit

[Buildkit](https://github.com/civicrm/civicrm-buildkit) is a collection of ~20 tools for developing and testing CiviCRM, the most important of which is [civibuild](/tools/civibuild.md).

Many of these tools are commonly used by web developers, so you may have already installed a few. Even so, it's generally easier to install the full collection &mdash; installing each individually takes a lot of work.

This is the same collection of tools which manages the test/demo/release infrastructure for civicrm.org.


## Installation

### Ubuntu

If you have a new installation of Ubuntu 12.04 or later, then you can download everything -- buildkit and the system requirements -- with one command. This command will install buildkit to `~/buildkit`:

```bash
curl -Ls https://civicrm.org/get-buildkit.sh | bash -s -- --full --dir ~/buildkit
```

!!! note

    * When executing the above command, you should *not* run as `root`, as it *will* cause failures. However, you *should*     have `sudo` permissions.
    * The `--full` option is *very opinionated*; it specifically installs `php`, `apache`, and `mysql` (rather than `hhvm`, `nginx`, `lighttpd`, or `percona`). If you try to mix `--full` with alternative systems, then expect conflicts.
    * If you use the Ubuntu feature for "encrypted home directories", then don't put buildkit in `~/buildkit`. Consider `/opt/buildkit`, `/srv/buildkit`, or some other location that remains available during reboot.

After running the above command, then proceed to the [post-installation configuration](#config).

### Vagrant

[Full Download: Vagrantbox](https://github.com/civicrm/civicrm-buildkit-vagrant) - Download a prepared virtual-machine with all system dependencies (mysql, etc). This is ideal if you work on Windows or OS X.


### Docker

If you have [Docker](https://www.docker.com/) running, you can use one of the following projects to run buildkit within a Docker container:

* <https://github.com/michaelmcandrew/civicrm-buildkit-docker>
* <https://github.com/progressivetech/docker-civicrm-buildkit>
* <https://github.com/ErichBSchulz/dcbk>

!!! Note

    There different version of Buildkit on Docker. Michael McAndrew's seems to be the easiest to get started with on Linux. There is an [outstanding issue](https://github.com/michaelmcandrew/civicrm-buildkit-docker/issues/15) that needs to be resolved before getting set up on a Mac is as straight forward.

#### Install buildkit on docker on ubuntu

Follow the official installation instructions from https://docs.docker.com/compose/install/ to install docker compose on your linux machine.

```bash
git clone https://github.com/michaelmcandrew/civicrm-buildkit-docker.git
cd civicrm-buildkit-docker
sudo docker-compose up -d
```

Now you are ready to go.

To create a new site with buildkit run the following command:

```bash
docker-compose exec -u buildkit civicrm civibuild create dmaster --url http://localhost:8080
```

Alternative you can login into the conatiner and run the commands from there:

```bash
docker-compose exec -u buildkit civicrm bash
```

More information is in the Readme: https://github.com/michaelmcandrew/civicrm-buildkit-docker/blob/master/README.md


### Other platforms

You may install buildkit in other environments. The main pre-requisites are:

* Linux or OS X
* Git
* PHP 5.3+ (Extensions: `bcmath curl gd gettext imap intl imagick json mbstring mcrypt openssl pdo_mysql phar posix soap zip`)
* NodeJS (v5 recommended)
* NPM
* Recommended (_for [amp](https://github.com/totten/amp) and [civibuild](/tools/civibuild.md)_)
    * Apache 2.2 or 2.4 (Modules: `mod_rewrite`. On SUSE, possibly `mod_access_compat`. This list may not be exhaustive.)
    * MySQL 5.1+ (client and server)

All pre-requisites must support command-line access using the standard command names (`git`, `php`, `node`, `mysql`, `mysqldump`, etc). In some environments, you may need to enable these commands by configuring `PATH` -- this is especially true for MAMP, XAMPP, and other downloaded packages. (See, e.g., [Setup Command-Line PHP](/standards/php.md).)

Once the pre-requisites are met, download buildkit to `~/buildkit`:

```bash
$ git clone https://github.com/civicrm/civicrm-buildkit.git ~/buildkit
$ cd ~/buildkit
$ ./bin/civi-download-tools
```


## Post-install configuration {:#config}

### Configuring your path {:#path}

!!! note "Not needed for Vagrant/Docker installations"
    If you set up buildkit using Vagrant or Docker, then you don't need to perform the configuration steps listed here.

Buildkit includes many CLI commands in the `bin/` folder.

You may execute the commands directly (e.g.  `./bin/civix` or `/path/to/buildkit/bin/civix`).  However, this would become very cumbersome.  Instead, you should configure the shell's `PATH` to recognize these commands automatically.

!!! tip
    Throughout this document, we will provide examples which assume that buildkit was downloaded to `/path/to/buildkit`. Be sure to adjust the examples to match your system.

If you want to ensure that the buildkit CLI tools are always available, then:

1. Determine the location of your shell configuration file. This is usually `~/.bash_profile`, or `~/.profile`. You may have to create one.
1. At the end of the file, add `PATH="/path/to/buildkit/bin:$PATH"`.
1. If you are on a mac, you can close and re-open your terminal. On other systems, you will need to log-out or source your `~/.profile` with `source ~/.profile`.
1. Enter the command `civibuild -h`. This should display a help screen for civibuild. If you get 'command not found', then check your path and retry the steps above.


!!! tip
    For most installations with the standard buildkit install script the following lines in your shell configuration file will work.
    ``` bash
    # Add ~/buildkit/bin to path if it exists.
    if [ -d "$HOME/buildkit/bin" ] ; then
      PATH="$HOME/buildkit/bin:$PATH"
    fi
    ```


!!! note "More on bash `$PATH`"

    On most OS's `~/.profile` is run only once when you login to your desktop. There is a distinction between "login shells" and "non-login shells" which you don't really need to worry about, except that the distinction is the reason that you should set your `$PATH` in your `~/.profile` and not your `~/.bashrc`.

    When you open a terminal (non-login), `~/.bashrc` will be executed. The common idiom for changing the path is to add to the `$PATH`, not rebuild it, so if you update your `$PATH` every time a shell is invoked, your `$PATH` will continually grow. This is not really a problem, but you might want to be aware of this.

    If you are on a mac, the situation is reversed. That is, your `$PATH` is not set when you login into your desktop and every terminal you open is a "login shell" and `~/.profile` will be executed every time.

    You do not need to run `export PATH=...` because your system certainly has already exported the `$PATH` variable and you only need to update it.

    References:

    * <https://unix.stackexchange.com/a/26059>
    * <https://superuser.com/questions/244964/mac-os-x-bashrc-not-working#244990>
    * <https://askubuntu.com/questions/155865/what-are-login-and-non-login-shells#156038>

!!! note

    Buildkit includes specific versions of some fairly popular tools (such as `drush`, `phpunit`, and `wp-cli`), and it's possible that you have already installed other versions of these tools.

    By design, buildkit can coexist with other tools, but you must manually manage the `PATH`.

    Whenever you wish to use buildkit, manually run a command like, e.g.:

    ```bash
    export PATH=/path/to/buildkit/bin:$PATH
    ```

    To restore your normal `PATH`, simply close the terminal and open a new one.

    Each time you open a new terminal while working on Civi development, you would need to re-run the `export` command.


### Configuring `amp` {:#amp-config}

Buildkit provides a tool called `amp` which [civibuild](/tools/civibuild.md) uses when it needs to set up a new site. Before you can use `civibuild`, need to configure `amp` by telling it a bit about your system (e.g. what webserver you're using).

1. Run the interactive configuration tool.

    ```
    $ amp config
    ```

    !!! tip "tips"
        * Run this as a non-`root` user who has `sudo` permission. This will ensure that new files are owned by a regular user, and (if necessary) it enables `civibuild` to restart your webserver and edit `/etc/hosts`.
        * Pay close attention to any instructions given in the output of this command.
        * To check which version of apache you have, run `apachectl -v`.

    !!! caution
        We strongly recommend using Apache as your webserver because support for nginx is limited.

1. Test amp's configuration

    ```
    $ amp test
    ```

    The test is successful if you see `Received expected response` at the end.

    If the test produces any errors, you might try re-running the above config steps and/or asking for help in the [developer chat room](https://chat.civicrm.org/civicrm/channels/dev).

1. After `amp` is configured, you can move on to running [civibuild](/tools/civibuild.md) to build a local development installation of CiviCRM.


## Troubleshooting {:#troubleshooting}

### Node JS issues
Nodejs version too old or npm update does not work:

: Download the latest version from nodejs.org and follow their instructions

Nodejs problems

: It might be handy to run

    ```bash
    npm update
    npm install fs-extra
    ```

### Website login issues

If you find that when you try and login to a new buildkit build or similar and it doesn't seem to login just redirects to the same page. This may mean that the rewrite module for apache is not enabled. To enable it do the following

```bash
sudo a2enmod rewrite
```

After enabling the rewite module you will need to restart apache.

## Upgrading buildkit {:#upgrading}

New versions of buildkit are likely to include new versions of tools. The new tools will download automatically when you first run `civibuild`. If you prefer to download explicitly, then re-run `civi-download-tools`.

The configurations and tools in buildkit are periodically updated. To get the latest, simply run:

```bash
cd ~/buildkit
git pull
./bin/civi-download-tools
```

See the [buildkit changelog](https://github.com/civicrm/civicrm-buildkit/blob/master/CHANGELOG.md) for info about specific changes to buildkit.

!!! tip "When upgrading `civix`, check upgrade instructions."

    If you see an upgrade to `civix` in the changelog, and if you maintain extensions with `civix`,
    then check the general [civix upgrade documentation](/extensions/civix.md#upgrade-templates) and [UPGRADE.md](https://github.com/totten/civix/blob/master/UPGRADE.md).
