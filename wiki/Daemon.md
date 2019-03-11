# Daemon и DaemonInterface

`\Small\Daemon.php`

Абстрактный класс и интерфейс для создания демонов.

Экземпляр конкретного демона должен наследовать абстрактный класс `Small\Daemon`
и реализовать интерфейс `Small\DaemonInterface`

## `protected $lastRunDate: string`
Дата и время последнего запуска итерации демона (строка в формате ISO-8601 `date('c')`).

## `protected $sleep: int`
Задержка между итерациями (в секундах).

## `protected $arguments: \Small\Arguments`
Экземпляр класса `Small\Arguments` для доступа к аргументам скрипта.

## `protected $terminal: \Small\Terminal`
Экземпляр класса `Small\Terminal` для вывода в терминал

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
Выводит сообщение с датой пропуска итерации.

## `protected function setLastRunDate(string $date = '')`
Устанавливает время последнего запуска. Если параметр `$date` не передан,
будет установлена текущая дата и время в формате ISO-8601 `date('c')`.

## Методы, обязательные для реализаии:

## `public function process(): void`
Метод, реализующий итерацию демона `Small\DaemonInterface` - **обязателен для
реализации**.

## `public function skip(): boolean`
Метод, проверяющий следует ли пропустить следующую итерацию (Напр.: отсутствие подключения к БД).
* `return false;` - не пропускать
* `return true;` - пропустить

## `public function environment(): \Small\DaemonInterface`
Метод, устанавливающий настройки окружения.


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