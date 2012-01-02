EdpSuperluminal
===============
Version 0.0.1

Introduction
------------
EdpSuperluminal is a ZF2 module that caches the Zend classes used by your
application into a single file. Including this file greatly reduces the
execution time of your application, as the calls to the standard autoloader are
almost entirely eliminated.

**Warning:** After installing and enabling this module, it may seem as though
your ZF2 application is running faster than the speed of light (superluminal).
Don't panic. This is just an illusion and no laws of physics are being violated.


Installation
------------

- Clone this module into your `vendor/` directory and enable `EdpSuperluminal`
- Add the following code to your `public/index.php` after chdir() and before including the AutoloaderFactory file:

    define('ZF_CLASS_CACHE', 'data/cache/classes.php.cache');
    if (file_exists(ZF_CLASS_CACHE)) require_once ZF_CLASS_CACHE;

- In your browser, go to http://yourapp/?buildCache=1 to build the initial
  class.
