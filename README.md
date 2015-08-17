# wp-app-showcase

WordPress plugin to create an App showcase of OpenData-Projects

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

This script runs on GitLab CI as well for every pull request.
