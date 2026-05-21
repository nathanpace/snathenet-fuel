# Snathe.net code

This repo contains everything needed for running snathe.net, using the FuelPHP framework:
- The most recent version of FuelPHP and related files can be found in the `/fuel/core`, `/fuel/packages` and `/fuel/vendor` directories.
- The website code itself can be found in `/fuel/app`.
- The `/assets` directory contains all site assets such as javascript files, css files and images.

## Usage

1. Copy the `/fuel/app/.env.example` file to `/fuel/app/.env` and fill in the missing values for the "home" information (`HOME_LOC` is the place name of your home location), phonecodes database, geocode API and weather API. You can change the default `HOME_TZ` value to any tz time zone identifier.  
(The filename has already been added to the .gitignore file so this will be automatically ignored if git commits/pushes are made to the repo)
2. Once the .env file is saved, copy everything to the directory which has been set up as the root for the hosting.

Assuming everything is correctly set up on the server, the site should be fully functional once refreshed.

## Notes

If the hosting already has FuelPHP installed, then all that *should* be needed to be copied over to the host are the `/fuel/app` and `/assets` directories, once the relevant .env file changes have been done.  

---  
---  
  

# Changes to `/fuel/core` files for compatibility with PHP versions > 8.0 (up to 8.3) #

With the 1.8.2 version of FuelPHP only being compatible with PHP8.0, it became clear that running this on any version later than this would cause problems. Therefore, a number of the core FuelPHP files have been amended to make the framework compatiable with PHP versions later than 8.0, up to 8.3.

The changes made so far have been detailed below, organised by file.  
*(It's likely that more changes may be required as further amendments are made to the site and new functionality is explored)*

`fuel/core/classes/database/mysqli/cached.php`
- lines 88-104: changed return type to void, added 'void' return type declaration, commented out both return statements, OutOfBoundsException is now thrown instead of returning false
- line 115: added 'mixed' return type declaration
- line 140: added 'void' return type declaration
- line 163: added 'bool' return type declaration
- line 177: added 'mixed' return type declaration
- line 205: added 'void' return type declaration
- line 218: added 'void' return type declaration

`fuel/core/classes/database/result.php`
- line 294: added 'int' return type declaration
- line 308: added 'mixed' return type declaration
- line 318: added 'int' return type declaration
- line 326: added 'void' return type declaration
- line 334: added 'void' return type declaration
- line 348: added 'bool' return type declaration

`fuel/core/classes/input/instance.php`
- line 451: added null coalesce statement to first argument of `strstr()` call

`fuel/core/classes/presenter.php`
- line 39: added null coalesce statement to argument in `ucfirst()` call

`fuel/core/classes/security.php`
- line 148: changed `static::` to `static::class` in call to `is_callable()`

---  
---  

# Changes to `/fuel/core` files for compatibility with PHP version 8.4 #

Further to the changes above, a few more of the of the core FuelPHP files have been amended to make the framework compatiable with PHP version 8.4.

The changes made so far have been detailed below, organised by file.  
*(It's likely that more changes may be required as further amendments are made to the site and new functionality is explored)*

`fuel/core/classes/database/connection.php`
- line 47: changed implicit nullable type declaration to an explict nullable type declaration by adding a question mark
- line 282: changed implicit nullable type declaration to an explict nullable type declaration by adding a question mark
- line 298: changed implicit nullable type declaration to an explict nullable type declaration by adding a question mark

`fuel/core/classes/database/mysqli/connection.php`
- lines 231-241: amended code to remove ping() function call as this has been deprecated

`fuel/core/classes/database/query/builder/select.php`
- line 63: changed implicit nullable type declaration to an explict nullable type declaration by adding a question mark

`fuel/core/classes/db.php`
- line 106: changed implicit nullable type declaration to an explict nullable type declaration by adding a question mark
- line 121: changed implicit nullable type declaration to an explict nullable type declaration by adding a question mark

`fuel/core/classes/errorhandler.php`
- line 72: removed E_STRICT error level as this is deprecated

`fuel/core/classes/input.php`
- line 40: changed implicit nullable type declarations to explict nullable type declarations by adding a question mark

`fuel/core/classes/input/instance.php`
- line 85: changed implicit nullable type declarations to explict nullable type declarations by adding a question mark