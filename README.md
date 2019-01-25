# Small
Some Small Helpers

## Installation
```
composer require avnovoselov/small
```

## API

* Daemon
    * [InterfaceDaemon::process()](#interface-daemon-process) - тело демона
    * [InterfaceDaemon::skip()](#interface-daemon-skip) - условия пропуска итерации
    * [InterfaceDaemon::environment()](#interface-daemon-environment) - настройки окружения
    * [AbstractDaemon::arguments()](#abstract-daemon-arguments) - настройки аргументов
    * [AbstractDaemon::$arguments](#abstract-daemon-$arguments) - список аргументов
    * [AbstractDaemon::run()](#abstract-daemon-run) - запуск демона
    * [AbstractDaemon::__construct()](#abstract-daemon-__construct) - конструктор
* Arguments


### Daemon
#### InterfaceDaemon и AbstractDaemon
Для создания нового демона следует расширить абстрактный класс `AbstractDaemon`
и реализовать интерфейс `InterfaceDaemon`.

<a name="interface-daemon-process" id="interface-daemon-process">`InterfaceDaemon::process()`</a> - метод, выполняемый в каждой итерации (тело демона)

`Counter.php`
```php
class Counter extends \Small\AbstractDaemon
{
    private $count = 0;

    public function process()
    {
        ++$this->count;
        echo PHP_EOL . " iteration run at: {$this->lastRunDate}";
        echo PHP_EOL . " iteration number: {$this->count}";
    }
}
```

если необходимо добавить условия выполнения итерации, следует реализовать метод
`InterfaceDaemon::skip`

<a name="interface-daemon-skip" id="interface-daemon-skip">`InterfaceDaemon::skip(): bool`</a> - метод, проверяющий удовлетворены ли условия для выполнения итерации.
Например подключение к БД.

```php
class Counter extends \Small\AbstractDaemon
{
    ...

    public function skip(): bool
    {
        return time() % 2;
    }
}
```

В примере выше метод `Counter::skip` пропускает итерации, запущенные в нечетные секунды

<a name="interface-daemon-environment" id="interface-daemon-environment">`InterfaceDaemon::environment(): InterfaceDaemon`</a> - метод, определяющий настройки окружения
(Настройки подключения в БД, отображение ошибок и т.д.).

```php
class Counter extends \Small\AbstractDaemon
{
    ...

    public function environment(): \Small\InterfaceDaemon
    {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);

        return $this;
    }
}
```

<a name="abstract-daemon-arguments" id="abstract-daemon-arguments">`AbstractDaemon::arguments(array $defaultArguments = []): \Small\InterfaceDaemon`</a> - метод,
определяющий аргументы вызова демона и устанавливающий значения по умолчанию.

`daemon/counter.php`
```php
$counterDaemon = new Counter('Counter');

$counterDaemon
    ->arguments(['count' => 0])
    ->run();
```

```shell
/> php daemon/counter.php --count=3
```

Для демона `$counterDaemon` доступен аргумент `--count`, со значения которого начнется счет
в примере выше, аргумент `--count` имеет значение по умолчанию `0`.

<a name="abstract-daemon-$arguments" id="abstract-daemon-$arguments">`AbstractDaemon::$arguments`</a> - экземпляр класса `\Small\Arguments`,
обеспечивающий доступ к аргументам запуска демона.

```php
class Counter extends \Small\AbstractDaemon
{
    public function environment(): \Small\InterfaceDaemon
        {
            $this->count = $this->arguments->get('count');

            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(0);

            return $this;
        }
}
```

<a name="abstract-daemon-run" id="abstract-daemon-run">`AbstractDaemon::run(): void`</a> - запуск демона

<a name="abstract-daemon-__construct" id="abstract-daemon-__construct">`AbstractDaemon::__construct(string $name, int $sleep = 1): AbstractDaemon`</a> - конструктор демона

* `$name` - имя демона
* `$sleep` - задержка между итерациями (в секундах)

### Arguments