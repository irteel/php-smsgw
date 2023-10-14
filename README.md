# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/irteel/php-smsgw.svg?style=flat-square)](https://packagist.org/packages/irteel/php-smsgw)
[![Total Downloads](https://img.shields.io/packagist/dt/irteel/php-smsgw.svg?style=flat-square)](https://packagist.org/packages/irteel/php-smsgw)
![GitHub Actions](https://github.com/irteel/php-smsgw/actions/workflows/main.yml/badge.svg)

With this application you can easily turn your mobile phone into the SMS Gateway for your applications.
 
You will get Admin Panel and Android application with this package. Admin Panel keeps track of all messages you sent using this API and Android application turns your mobile into SMS Gateway. All the requests that you send will be first stored in Server using Admin Panel and then it will be handed over to the Android application. The android application sends the SMS according to the request and reports the status of the messages to the Admin Panel.
 
Features
 
Send SMS from your application developed using any programming language.
Use CSV or Excel file containing numbers and messages in first two columns to send bulk messages.
Shows status of messages sent using SMS Gateway in Admin Panel.
Ability to receive messages in Admin Panel and respond to it using a WebHook.
Ability to sign in using multiple Android devices to split messages between them when sending bulk messages.
Ability to create other users to let them use SMS Gateway from their mobile phones.

## Installation

You can install the package via composer:

```bash
composer require irteel/php-smsgw
```

## Usage

```php
// Usage description here
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email cyrille.bidongo@gmail.com instead of using the issue tracker.

## Credits

-   [Cyrille Bekono](https://github.com/irteel)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com) by [Beyond Code](http://beyondco.de/).
