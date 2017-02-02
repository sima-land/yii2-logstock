Testing Extension for Yii 2
===========================

This extension provides recording and asserting application trace. Based on yii\debug module.

Support
-------
1. Unit
2. Functional (without web server) - does not support
3. Acceptance (throughout web server)

[![Build Status](https://travis-ci.org/pastuhov/yii2-logstock.svg)](https://travis-ci.org/pastuhov/yii2-logstock)
[![Total Downloads](https://poser.pugx.org/pastuhov/yii2-logstock/downloads)](https://packagist.org/packages/pastuhov/yii2-logstock)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist pastuhov/yii2-logstock
```

or add

```
"require-dev": {
    "pastuhov/yii2-logstock": "~1.0.0"
    ...
```

to the require section of your `composer.json` file.

Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
...
if (YII_ENV_TEST) {
    // configuration adjustments for 'test' environment
    $config['bootstrap'][] = 'logstock';
    $config['modules']['logstock'] = [
        'class' => \pastuhov\logstock\Module::class,
    ];

}
...
```

Add `pastuhov\logstock\UnitHelper` to suit config

```yml
modules:
    enabled:
        - pastuhov\logstock\UnitHelper
```

Use assertLog() method in tests
```php
...
    public function testExampleUnitUsage()
    {
        $this->tester->assertLog(function (){
            Yii::info('Test info message');
            Yii::$app->getDb()->createCommand('SELECT * FROM page')->execute();
        }, Yii::$app);
    }
...
```

First time trace will be recorded

```
1) Test example unit usage (unit\ExampleUnitTest::testExampleUnitUsage)
Fixture has aggregated. Please restart test!
```

Recorded trace log:
```
Entry: console

Test info message

SELECT * FROM page
```
in subsequent test runs log will be compared

For more info see tests.

Testing
-------

```bash
./vendor/bin/codecept run unit,acceptance
```

If web server needed:

```bash
./tests/bin/yii serve&
```

Security
--------

If you discover any security related issues, please email pastukhov_k@sima-land.ru instead of using the issue tracker.