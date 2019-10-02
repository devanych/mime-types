# Mime Types

This PHP package allows you to convert MIME types to file extensions and Vice versa, and to add your own MIME types and file extensions.

You can change the functionality by implementing interfaces:

* [Devanych\Mime\MimeTypesInterface](https://github.com/devanych/mime-types/blob/master/src/MimeTypesInterface.php) - contains methods to implement the functionality.

* [Devanych\Mime\MimeTypesMapsInterface](https://github.com/devanych/mime-types/blob/master/src/MimeTypesMapsInterface.php) - contains a map of MIME types and file extensions.

This package requires PHP version 7.2 or later.

## Installation

This library is installed using the composer:

```
composer require devanych/mime-types
```

## Usage

Creation:

```php
use Devanych\Mime\MimeTypes;

$mimeTypes = new MimeTypes(array $map = []);
```

Conversion:

```php
/**
 * Gets the MIME types for the given file extension.
 *
 * @param string $extension
 * @return string[] an array of MIME types or an empty array if no match is found
 */
$mimeTypes->getMimeTypes('jpeg'); // ['image/jpeg', 'image/pjpeg']

/**
 * Gets the file extensions for the given MIME type.
 *
 * @param string $mimeType
 * @return string[] an array of extensions or an empty array if no match is found
 */
$mimeTypes->getExtensions('image/jpeg'); // ['jpeg', 'jpg', 'jpe']
```

Adding:

```php
/**
 * Adds a custom map of MIME types and file extensions.
 *
 * The key is a MIME type and the value is an array of extensions.
 *
 * Example code:
 * $map = [
 *    'image/ico' => ['ico'],
 *    'image/icon' => ['ico'],
 *    'image/jp2' => ['jp2', 'jpg2'],
 *    'image/jpeg' => ['jpeg', 'jpg', 'jpe'],
 *    'image/jpeg2000' => ['jp2', 'jpg2'],
 * ];
 *
 * If the map format is invalid, an `\InvalidArgumentException` will be thrown when the map is added.
 *
 * @param array $map
 */
$mimeTypes->addMap($map);
```

> You can pass a map to the constructor when you create a `Devanych\Mime\MimeTypes` class, inside the constructor calls the `addMap()` method.