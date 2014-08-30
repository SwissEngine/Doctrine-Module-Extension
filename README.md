SwissEngine\Tools\Doctrine\Extension
===============
This small module aims at providing a simple way to add a few features (seamlessly) to the doctrine CLI tool (ZF2) such as the ability to specify the entity manager we want to use optionally.

`php public/index.php orm:validate-schema --em=orm_custom`

Installation
------------

Suggested installation method is through [composer](http://getcomposer.org/):

```php
php composer.phar require swissengine/doctrine-module-extension:dev-master
```

Setup
-----

If you use Zend Framework 2, you can now enable this module in your application by adding it to `config/application.config.php` as `SwissEngine\Tools\Doctrine\Extension`.