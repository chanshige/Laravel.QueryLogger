# Laravel.QueryLogger

Application上で実行されるSQLクエリを全てlogへ出力します。  

** notice **  
    APP_DEBUG/APP_ENV 等のモードに依存していません。  
    composer "--dev" オプションを外してインストールした場合、  
    production環境等でも常に出力されることとなります。ご注意ください。  

## Installation
with composer (require-dev)
```shell script
$ composer require --dev chanshige/laravel-query-logger
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
