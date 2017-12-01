# Оbsidian Twig

Enables the use of Twig templates in Obsidian.

## Quickstart

1. Run `composer require htmlburger/obsidian-twig` in your theme directory
1. Add `\ObsidianTwig\Templating\ServiceProvider` to your array of providers when booting Obsidian:
    ```php
    \Obsidian\Framework::boot( [
        'providers' => [
            \ObsidianTwig\Templating\ServiceProvider::class,
        ],
    ] );
    ```
1. Replace the current template engine by adding this immediately after `\Obsidian\Framework::boot()`:
    ```php
    $container = \Obsidian\Framework::getContainer();
    $container['framework.templating.engine'] = $container->raw( 'obsidian_twig.templating.engine' );
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
$container = \Obsidian\Framework::getContainer();
$container['obsidian_twig.templating.engine.options'] = [
    'twig' => [
        'cache' => false, // disable cache (NOT advisable for production use)
        // ... other options
    ]
    // ... other options
];
```

More information on what Twig options are supported is available on https://twig.symfony.com/doc/2.x/api.html