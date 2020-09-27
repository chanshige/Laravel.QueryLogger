[![Packagist](https://img.shields.io/badge/packagist-v1.0.0-blue.svg)](https://packagist.org/packages/chanshige/laravel-query-logger)
[![Build Status](https://travis-ci.com/chanshige/Laravel.QueryLogger.svg?branch=master)](https://travis-ci.com/chanshige/Laravel.QueryLogger)

# Laravel.QueryLogger

Application上で実行されるSQLクエリを全てlogへ出力します。  

** notice **  
    APP_DEBUG/APP_ENV 等のモードに依存していません。  
    composer "--dev" オプションを外してインストールした場合、  
    production環境等でも常に出力されることとなります。ご注意ください。  

## Installation
with composer (require-dev)
```shell script
$ composer require --dev chanshige/laravel-query-logger:v1.0
```

## Usage
remove cache  
```
$ php artisan clear-compiled
```
  
for logger (illuminate/log, monolog)  
```
$ tail -f storage/log/laravel.log
```

##### When specifying any log driver
append to config/logging.php
```
'query_logger' => [
    'driver' => 'stack'
]
```
