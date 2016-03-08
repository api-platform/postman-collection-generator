# PostmanGeneratorBundle

Generator for [Postman](https://www.getpostman.com) collection based on [API Platform](https://api-platform.com/).

[![Build Status](https://travis-ci.org/api-platform/postman-collection-generator.svg?branch=master)](https://travis-ci.org/api-platform/postman-collection-generator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/api-platform/postman-collection-generator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/api-platform/postman-collection-generator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/api-platform/postman-collection-generator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/api-platform/postman-collection-generator/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/281cec32-d5dc-4afe-9aee-8a704f1025f9/mini.png)](https://insight.sensiolabs.com/projects/281cec32-d5dc-4afe-9aee-8a704f1025f9)
[![Dependency Status](https://www.versioneye.com/user/projects/56d1d4b3157a69002ea956f7/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56d1d4b3157a69002ea956f7)

**This bundle is still in progress. You will be notified soon of its first release ;).**

Feel free to contribute on it !

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

This bundle provides a unique command to automatically generate a Postman collection based on your API Platform
project configuration. Run `php app/console postman:collection:build --help` for more details.
