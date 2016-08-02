# PostmanGeneratorBundle

**This bundle is not necessary anymore if you use API Platform 2.0 or superior.** API Platform now supports natively [Swagger](http://swagger.io/) and Postman is able to create collections from a Swagger documentation. 

Generator for [Postman](https://www.getpostman.com) collection based on [API Platform](https://api-platform.com/).

[![Build Status](https://travis-ci.org/api-platform/postman-collection-generator.svg?branch=master)](https://travis-ci.org/api-platform/postman-collection-generator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/api-platform/postman-collection-generator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/api-platform/postman-collection-generator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/api-platform/postman-collection-generator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/api-platform/postman-collection-generator/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/281cec32-d5dc-4afe-9aee-8a704f1025f9/mini.png)](https://insight.sensiolabs.com/projects/281cec32-d5dc-4afe-9aee-8a704f1025f9)
[![Dependency Status](https://www.versioneye.com/user/projects/56d1d4b3157a69002ea956f7/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56d1d4b3157a69002ea956f7)

## Installation

Install this bundle through [Composer](https://getcomposer.org/):

```bash
composer require --dev api-platform/postman-collection-generator
```

Using Symfony, update your `AppKernel.php` file:

```php
public function registerBundles()
{
    ...
    if ($this->getEnvironment() != 'prod') {
        ...
        $bundles[] = new PostmanGeneratorBundle\PostmanGeneratorBundle();
    }
}
```

## Configuration

This library requires some configuration. Edit your `app/config_dev.yml` file as following:

```yml
postman_generator:
    name: Name of your API                  # Required
    description: Description of your API    # Optional, default: null
    baseUrl: http://www.example.com         # Required
    public: false                           # Optional, default: false
    authentication: oauth2                  # Optional, default: null
    defaultLocale: fr_FR                    # Optional, default: en_GB
```

## Usage

This bundle provides a unique command to automatically generate a Postman collection based on your API Platform
project configuration. Run `php app/console postman:collection:build --help` for more details.

## Use parsers

This library provides a simple way to extend it, called `parsers`. There are 2 of them: request parsers & command
parsers.

### Request parsers

Request parsers are services executed to edit [Postman requests](src/Model/Request.php) before being sent to collection.
You can, for example, add a custom authentication header, add some tests, etc.

To create your own request parser, your service must implement `PostmanGeneratorBundle\RequestParser\RequestParserInterface`,
and has a tag `postman.request_parser`.

**Careful**: some request parsers may be executed before yours. Check for [`priority`](http://symfony.com/doc/current/reference/dic_tags.html)
process in Symfony Dependency Injection.

### Command parsers

Command parsers are services executed to connect to the main command, for example to ask for authentication access.

To create your own command parser, your service must implement `PostmanGeneratorBundle\CommandParser\CommandParserInterface`,
and has a tag `postman.command_parser`. `parse` method allows you to ask questions to user, and `execute` method to do
your stuff.

**Careful**: some command parsers may be executed before yours. Check for [`priority`](http://symfony.com/doc/current/reference/dic_tags.html)
process in Symfony Dependency Injection.

## Authentication

By default, this library can manage [OAuth2](http://oauth.net/2/) authentication. To use it, fill `authentication`
configuration key using `oauth2`. When using main command, you will be prompt for some login/password. They will be
managed as environment variables in Postman.

Feel free to add your own authenticators as request & command parsers.
