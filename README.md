# Laravel.QueryLogger

Application上で実行されるSQLクエリを全てlogへ出力します。

## Installation
with composer
```shell script
$ composer require --dev chanshige/laravel-query-logger
```

## Usage
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
