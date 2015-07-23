# tinkr

tinkr is a testbed console thing for PHP. one day I will come up with a better
explanation. for now, just know that you can do this:

```bash
$ tinkr erusev/parsedown
Preparing tinkr v0.5.0...
Loading composer repositories with package information
Installing dependencies (including require-dev)
  - Installing erusev/parsedown (1.5.3)
    Loading from cache

Writing lock file
Generating autoload files
Psy Shell v0.5.2 (PHP 5.6.9 — cli) by Justin Hileman
>>> (new Parsedown)->parse("Hello tinkr.")
=> "<p>Hello tinkr.</p>"
>>> exit
Exit:  Goodbye.
Cleaning up temporary tinkr environment...
```

## Usage

```bash
$ tinkr [--path=PATH|--use-current-dir] [PACKAGE_1] [PACKAGE_N]
```

If called without any arguments, tinkr essentially behaves like it's underlying
PsySh, with the exception that you end up in a sandboxed environment. But of course,
the actual benefit of tinkr is passing composer package names for quickly
testing them without having to manually require-write-script-run the test.

## Export

Tinkr sessions are by default temporary because they are just meant for quick
testing without all the hassle of composer require and autoloading and stuff.
If you need the things saved, it's possible to export a session to a permanent
location by calling the `export` command in the session. This will write the
session to a sub-directory your current dir. If you want a specific storage
path, you can pass that as an argument, e.g. `export ~/my_tinkr_session`.

Exported sessions will not be overridden unless you run the command as `export --force`.

In the future, tinkr will try to generate a PHP script from the command history,
for now, the export just contains the bare environment, which, however, allows for
reopening in tinkr with `$ tinkr --path your-exported-tinkr-session`.

## Installation

via composer:

```bash
$ composer global require efrane/tinkr
```

**Don't forget to add your .composer/vendor/bin to your $PATH**

## Why?

License: MIT

