# Arguments

`\Small\Arguments.php`

Класс для работы с аргументами скрипта, вызванного через терминал.

```php
// foo.php
$arguments = new \Small\Arguments([
    "verbose"   => 0,
    "name"      => "User",
]);
```

## `protected $arguments: array`
Хранилище аргументов

## `public function get(string $name)`
Получение значения аргумента `$name`

```php
...
if ($arguments->get('verbose')) {
    echo `Hi, {$arguments->get('name')}!`;
}
```

```shell
/> php foo.php --verbose=1 --name=Developer
/> Hi, Developer!
```

## `public __construct(array $defaultArguments = []): \Small\Arguments`
Конструктор класса. Принимает массив с дефолтными значениями аргументов.

Все аргументы, которые были переданы в `$defaultArguments` будут доступны
через `$arguments->get(<name>)`, все остальные аргументы **доступны не будут**.