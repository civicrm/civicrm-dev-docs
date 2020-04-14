The `universe` is the list of knowable codes, tools, add-ons, integrations, etc for CiviCRM.  The `universe` includes:

 * The standard CiviCRM git repositories (`civicrm-core.git`, `civicrm-packages.git`, etc).
 * Any extensions registered on `civicrm.org` that have a Git URL.
 * Most infrastructure and supporting components behind `civicrm.org`.

The `universe` can help you analyze the technical state of the CiviCRM community's code.  For example, suppose you want to change the
signature of a function in `civicrm-core.git` named `getContactDetails(...)`.  You can get a copy of the universe and search the entire
source tree for `getContactDetails` to see how it's being used.

!!! tip "Use a fast network and fast storage device (SSD)"

    The `universe` is fairly big.  (At time of writing, ~2 GB.) A fast network will help with downloading, and a fast storage will help
    with searching.

## Create the universe

If your system is configured to support [civibuild](civibuild.md), then simply run:

```bash
$ civibuild create universe
```

Alternatively, if you have a copy of `buildkit` but don't use `civibuild`, then run:

```bash
$ mkdir ~/src/universe
$ fetch-universe ~/src/universe
```

## Search the universe

Note the path to your copy (eg `~/buildkit/build/universe` or `~/src/universe`) and `cd` to it.

```bash
$ cd ~/buildkit/build/universe
```

You can get a lot of information with standard Unix tools like `grep`, eg

```bash
$ grep -r getContactDetails .
```

There's a lot you can do with `grep`, such as:

```bash
# Case insensitive search
$ grep -ri getContactDetails .

# Ignore folders like `.git` or `.svn`
$ grep -r --exclude-dir=.git --exclude-dir=.svn getContactDetails .

# Edit all matching files
$ vi $(grep -ril getContactDetails .)
```

Of course, there's nothing special about `grep` here.  Lots of other powerful tools will do the job, such as
[`ack`](https://beyondgrep.com/) or [the Silver Searcher](https://github.com/ggreer/the_silver_searcher).  The author of `ack` has
published a longer [list of relevant tools](https://beyondgrep.com/more-tools/).

!!! tip "Why would you search `universe`?"

    Continuing the example, you might argue, "`getContactDetails` isn't officially an API, so it's fair-game to change whenever we want.
    Searching the universe doesn't add anything."

    In *policy* terms, that might be right...  but is it really a safe change in *practical* terms?  Most of the time...  probably!  But
    some of the time, Murphy's Law kicks in -- changing `getContactDetails(...)` might break 10 extensions.  Arguably, the fault lies with
    the extension author who called a non-API -- but that will bring little comfort to the 20 users who show up on StackExchange asking for
    help, and it will still reflect badly on all of us.

    Searching `universe` is a simple way to get ahead of that risk -- and to make decisions based on *empirical data* rather than
    proscriptive notions.

!!! tip "What to do if searching `universe` reveals a technical conflict?"

    The `universe` is just a tool -- it's a way to get ahead of problems (by making them easier to discover).  It's not an over-arching policy on
    what to do if you find a conflict.  Returning to our `getContactDetails` example:

       * Maybe you should change the approach -- keep the signature of `getContactDetails` as-is, but change something else.
       * Or maybe the extensions should be updated to match the new signature.
       * Or maybe you should give a heads-up to the other affected developers.

    We're not trying to pre-judge the solution here.  Main advice: start from the assumption that this is a shared problem.  Finding a
    solution is good for you, for the other developers, and for the users.  Encourage others to view it the same way.

## Update the universe

The `universe` is ever-expanding -- eg projects are updated, and new projects are added.  Generally, it's not important to be accurate
"up-to-the-minute".  But you may want to update if your copy of the universe is more than a week or two old.

Simply note the path to your copy (eg `~/buildkit/build/universe` or `~/src/universe`) and run `fetch-universe`:

```bash
$ fetch-universe ~/buildkit/build/universe
```
