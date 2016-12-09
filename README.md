# wp-app-showcase

WordPress plugin to create an app showcase of OpenData-projects

## Requirements

* [CMB2](https://wordpress.org/plugins/cmb2/) plugin must be installed

## Installation

Just activate the plugin in the WordPress plugin administration.

If you have the wp-ckan-backend plugin activated in your instance you will automatically get an autocomplete feature to select related datasets while creating/editing apps.

## Development

1. Install composer if it isn't installed system wide:
    ```
   $ curl -sS https://getcomposer.org/installer | php
   ```

1. Run `php composer.phar install` to install dependencies

1. add wordpress-standard to phpcs: `./bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs`


To check the code style, run the build script:

```
$ ./build.sh
```

This script runs on Travis CI as well for every push.

## Extract messages / Compile translation files

Run the following script to extract messages from php-files and generate a new wp-ogdch-theme.pot file:

```
$ ./extract_messages.sh
```

To compile all .po files to .mo files use the following script:

```
$ ./compile_translation_files.sh
```
