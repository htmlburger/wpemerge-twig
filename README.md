# WP Emerge Twig

Enables the use of Twig templates in WP Emerge.

## Quickstart

1. Run `composer require htmlburger/wpemerge-twig` in your theme directory
1. Add `\WPEmergeTwig\Templating\ServiceProvider` to your array of providers when booting WPEmerge:
    ```php
    WPEmerge::boot( [
        'providers' => [
            \WPEmergeTwig\Templating\ServiceProvider::class,
        ],
    ] );
    ```
1. Replace the current template engine by adding this immediately after `WPEmerge::boot()`:
    ```php
    $container = WPEmerge::getContainer();
    $container[ WPEMERGE_TEMPLATING_ENGINE_KEY ] = $container->raw( 'wpemerge_twig.templating.engine' );
    ```

## Options

Default options:
```php
[
    'views' => ABSPATH,
    'twig' => [ // options passed directly to Twig
        'cache' => get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'twig',
    ]
]
```

You can use this to change the default options:
```php
$container = WPEmerge::getContainer();
$container['wpemerge_twig.templating.engine.options'] = [
    'twig' => [
        'cache' => false, // disable cache (NOT advisable for production use)
        // ... other options
    ]
    // ... other options
];
```

More information on what Twig options are supported is available on https://twig.symfony.com/doc/2.x/api.html

## Extending Twig

You can use the following to extend twig with a custom filter, for example:
```php
$myfilter = new Twig_Filter( 'myfilter', function( $string ) {
    return strtoupper( $string );
} );

$twig = WPEmerge::resolve( 'wpemerge_twig.templating.engine' );
$twig->environment()->addFilter( $myfilter );
```
With this, you now have your very own custom Twig filter:
```twig
{{ 'hello world!' | myfilter }}
```

More information on how you can extend Twig is available on https://twig.symfony.com/doc/2.x/advanced.html#creating-extensions
