# PostmanGeneratorBundle

Generator for [Postman](https://www.getpostman.com) collection based on [API Platform](https://api-platform.com/).

Build Status: [![Build Status](https://secure.travis-ci.org/vincentchalamon/PostmanGeneratorBundle.png?branch=master)](http://travis-ci.org/vincentchalamon/PostmanGeneratorBundle)

## Installation

Install this bundle through [Composer](https://getcomposer.org/):

```bash
composer require --dev vince/postman-generator-bundle
```

This bundle provides a unique command to automatically generate a Postman collection based on your API Platform
project configuration. Run `php app/console postman:collection:build --help` for more details.
