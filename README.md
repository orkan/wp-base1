# Wordpress plugin: Ork Base1 example `v2.0.0`
Basic example demonstrating usage of [orkan/wp-base](https://github.com/orkan/wp-base) package.

### Installation:
`$ composer require orkan/wp-base1`

## Tools:
This package comes with some example Tools:
```php
$Factory->Mailer()->run(); // Example with WP Mail form
$Factory->Formix()->run(); // Example how to create custom FORM with various inputs
$Factory->Ajaxer()->run(); // Example how to handle Ajax requests
```
... and their CSS/JS assets (`/assets` dir) and FORM input definitions ( `/config` dir).

Links to these Tools will be automatically displayed in the: Dashboard > Plugins > Ork Base1 - meta row.

## Requirements:
- PHP ^7
- Composer ^2
- WordPress ^6

## License:
[MIT](https://github.com/orkan/wp-base1/LICENCE)

## Author
[Orkan](https://github.com/orkan)

## Updated
Fri, 17 May 2024 16:31:19 +02:00
