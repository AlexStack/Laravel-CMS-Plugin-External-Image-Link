# Save external images to your server and add rel=nofollow to the external links

-   This is an Amila Laravel CMS Plugin

## Install it via command line manually

```php
composer require alexstack/laravel-cms-plugin-external-image-link

php artisan migrate --path=./vendor/alexstack/laravel-cms-plugin-external-image-link/src/database/migrations

php artisan vendor:publish --force --provider=Amila\\LaravelCms\\Plugins\\ExternalImageLink\\LaravelCmsPluginServiceProvider

php artisan laravelcms --action=clear

```

## How to use it?

-   By default save image to your server and add rel="nofollow" to external links are enabled
-   You don't need to do anything after install

## How to change the settings?

-   You can change the settings by edit plugin.page-tab-external-image-link

```json
{
    "plugin_name": "External Images & Links",
    "blade_file": "remote-image",
    "tab_name": "aac",
    "php_class": "Amila\\LaravelCms\\Plugins\\ExternalImageLink\\Controllers\\ExternalImageLinkController",
    "version": "0.1.1",
    "remote_image_to_local": {
        "enable": true,
        "exclude": [".laravelcms.tech", "localhost/", ".test", ".local"],
        "local_image_size": "original",
        "replace_fields": [
            "main_content",
            "sub_content",
            "extra_content_1",
            "extra_content_2",
            "extra_content_3"
        ]
    },
    "nofollow_external_links": {
        "enable": true,
        "exclude": ["localhost/", ".test", ".laravelcms.tech"],
        "rel_text": "nofollow noopener external noindex",
        "target": "_blank",
        "replace_fields": [
            "main_content",
            "sub_content",
            "extra_content_1",
            "extra_content_2",
            "extra_content_3"
        ]
    }
}
```

## License

-   This Amila Laravel CMS plugin is an open-source software licensed under the MIT license.
