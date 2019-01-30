# Daemon

`\Small\Daemon.php`

Абстрактный класс для создания демонов.

## `protected $lastRunDate: string`
Дата и время последнего запуска итерации демона (строка в формате ISO-8601 `date('c')`).

## `protected $sleep: int`
Задержка между итерациями (в секундах).

## `protected $arguments: \Small\Arguments`
Экземпляр класс `Small\Arguments` для доступа к аргументам скрипта.

## `public function printWelcome(string $name): void`
Выводит приветственное сообщение.

## `final public function __construct(string $name, int $sleep = 1)`
Конструктор класса.

`$name` &mdash; Имя скрипта.

`$sleep` &mdash; Задержка между итерациями (в секундах).

## `final public function run()`
Запуск демона - реализует бесконечный цикл, с задержкой `$sleep` между итерациями.

## `protected function printName()`
Выводит сообщение с именем демона.

## `protected function printStartDate()`
Выводит сообщение с временем демона.

## `protected function printSkip()`
Выводит сообщение с причиной пропуска итерации.

## `protected function setLastRunDate(string $date = '')`
Устанавливает время последнего запуска. Если параметр `$date` не передан,
будет установлена текущая дата и время в формате ISO-8601 `date('c')`.


```php
// Counter.php
class Counter extends \Small\Daemon
{
    /**
     * Кол-во итераций
     */
    private $count = 0;

    /**
     * Выводит Дату и время и кол-во итераций
     */
    public function process()
    {
        ++$this->count;
        $this->terminal->info("iteration run at:", $this->lastRunDate);
        $this->terminal->info("iteration number:", $this->count);
        $this->terminal->info("work", $this->getWorkTime(), "sec");
    }

    /**
     * Никогда не пропускать итерации
     */
    public function skip(): bool
    {
        return false;
    }

    /**
     * Устанавливает переменные окружения
     */
    public function environment(): \Small\DaemonInterface
    {
        ini_set('memory_limit', '4M');
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);

        return $this;
    }
}
```