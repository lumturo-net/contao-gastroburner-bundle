# Contao Gastroburner Bundle

Only for internal use... ABC

## Customize


Then rename the following files and/or the references to `SkeletonBundle` in
the following files:

 * `src/ContaoManager/Plugin.php`
 * `src/DependencyInjection/ContaoSkeletonExtension.php`
 * `src/ContaoSkeletonBundle.php`
 * `tests/ContaoSkeletonBundleTest.php`

Finally add your custom classes and resources.

## Release

Run the PHP-CS-Fixer and the unit test before you release your bundle:

```bash
vendor/bin/php-cs-fixer fix -v
vendor/bin/phpunit
```

[1]: https://contao.org

