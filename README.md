# Packet

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is a simple package to create a workbench in your (Laravel)[http://laravel.com] project.   
PSR4 Support.

## Install

Via Composer

``` bash
$ composer require mosaiqo/packet
```

## Usage
Copy this line in to the providers array
``` php
'providers' => [
	...
	Mosaiqo\Packet\PacketServiceProvider::class,
	...
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email boudydegeer@mosaiqo.com instead of using the issue tracker.

## Credits

- [Boudy de Geer][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mosaiqo/packet.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/mosaiqo/mosaiqo/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/mosaiqo/packet.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/mosaiqo/packet.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mosaiqo/packet.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mosaiqo/packet
[link-travis]: https://travis-ci.org/mosaiqo/packet
[link-scrutinizer]: https://scrutinizer-ci.com/g/mosaiqo/packet/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/mosaiqo/packet
[link-downloads]: https://packagist.org/packages/mosaiqo/packet
[link-author]: https://github.com/boudydegeer
[link-contributors]: ../../contributors
