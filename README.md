# Save external images to your server and add rel=nofollow to the external links

-   This is an Amila Laravel CMS Plugin
-   Save all remote images in the page contents editor to your server, replace the image URL from remote URL to your local URL. Add rel=nofollow & target=\_blank to external links for better SEO and user experience.

## Install it via the backend

-   Go to the CMS settings page -> Plugin -> search for remote image
-   Find alexstack/laravel-cms-plugin-external-image-link
-   Click the Install button

## What the plugin do for us?

-   Save the remote images in the content editors to your server (eg. Main Content, Sub Content, Extra Content ...). You will be able to find them on the File Manager page afterward.
-   Automatically convert the remote image URLs to the local relative URLs. eg.

```php
<img src="https://github.githubassets.com/images/modules/marketplace/marketplace-illustration-01.svg" class="..." >

will automatically convert to below for you:

<img src="/laravel-cms/uploads/fb/9a3f5ebfa6aec1a597094ad6d3116edc09e2e2fb.svg" class="..." >
```

-   Automatically add rel="nofollow" & target="\_blank" to external links for better SEO and user experience. eg.

```php
<a href="https://github.com/AlexStack/Laravel-CMS" class="text-info">Laravel CMS</a>

will automatically convert to below for you:

<a href="https://github.com/AlexStack/Laravel-CMS" class="text-info" target="_blank" rel="nofollow noopener external noindex">Laravel CMS</a>

```

## Install it via command line manually

```php
composer require alexstack/laravel-cms-plugin-external-image-link

php artisan migrate --path=./vendor/alexstack/laravel-cms-plugin-external-image-link/src/database/migrations

php artisan vendor:publish --force --tag=external-image-link-views

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
    "tab_name": "",
    "php_class": "Amila\\LaravelCms\\Plugins\\ExternalImageLink\\Controllers\\ExternalImageLinkController",
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

## How to send http header before grab the remote image?

-   Examples are below:

```json
"remote_image_to_local" : {
  	"enable": true,
    "exclude":[".laravelcms.tech","localhost/",".test", ".local"],
    "local_image_size": "original",
  	"replace_fields": ["main_content","sub_content","extra_content_1","extra_content_2","extra_content_3"],
  	"stream_options": {
     	".laravelcms.tech" : {
         	"http" : {
              	"method" : "GET",
              	"header" : "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:69.0) Gecko/20100101 Firefox/69.0\r\nReferer:https://www.amazon.com/"
            }
        },
     	".laravel.test" : {
         	"http" : {
              	"method" : "GET",
              	"header" : "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36\r\nReferer:https://www.laravelcms.tech/"
            }
        }
    }
},
```

## Improve this plugin & documents

-   You are very welcome to improve this plugin and how to use documents

## License

-   This Amila Laravel CMS plugin is an open-source software licensed under the MIT license.
