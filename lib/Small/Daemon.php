<?php


namespace Small;


/**
 * Class Daemon
 * @package Small
 */
abstract class Daemon extends Script implements DaemonInterface
{
    /**
     * Дата последней итерации демона в формате ISO-8601
     *
     * @var string
     */
    protected $lastRunDate;

    /**
     * Задержка между итерациями демона
     * указывается в секундах
     *
     * @var int
     */
    protected $sleep;

    /**
     * @var Arguments
     *  Объект с аргументами запуска скрипта
     */
    protected $arguments;

    /**
     * Вывод приветственного сообщения при запуске демона
     *
     * @return void
     */
    public function printWelcome()
    {
        $this->printName();
        $this->printStartDate();
    }

    /**
     * @inheritdoc
     */
    public function environment(): DaemonInterface
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function skip(): bool
    {
        return false;
    }

    /**
     * Конструктор класса
     *    пример вызова:
     *
     *    file: Foo.php
     *    class Foo extends \Small\AbstractDaemon {
     *        public function process() {
     *            echo PHP_EOL . "bar==" . $this->arguments->get('bar');
     *            // your code here
     *        }
     *    }
     *
     *    file: daemon/foo.php
     *    $foo = new Foo('Foo', 3, true);
     *    $foo->arguments([
     *       'bar' => 1,
     *    ]);
     *
     *    shell/> php daemon/foo.php --bar=5
     *        bar==5
     *
     * Имя демона
     * @param string $name
     * @param int $sleep
     * @return static $this;
     * @see Daemon::$sleep
     *
     * Флаг, могут ли быть пропущенные итерации (напр.: отсутствие соединение с БД)
     *
     * @see AbstractScript::name
     *
     * Задержка между итерациями
     */
    final public function __construct(string $name, int $sleep = 1)
    {
        parent::__construct($name);

        $this->sleep = $sleep;

        return $this;
    }

    /**
     * Запуск демона
     *    реализует бесконечный цикл, в каждой итерации которого выполняет метод @return void
     * @see DaemonInterface::process
     *
     */
    final public function run()
    {
        $this->printWelcome();

        for (; ;) {
            // проверяет удовлетворены ли все зависимости
            // для выполнения итерации
            if ($this->skip()) {
                $this->printSkip();
                sleep($this->sleep);
                continue;
            }

            $lastRunDate = date('c');
            // запускаем итерацию
            static::process();

            $this->setLastRunDate($lastRunDate);
            // ждем установленное количество секунд для выполнения следующей итерации
            sleep($this->sleep);
        }
    }

    /**
     * Выводит сообщение вида
     *    > Daemon: <имя демона>
     *
     * @return void
     */
    protected function printName()
    {
        $this->terminal->success("Daemon:", $this->name);
    }

    /**
     * Выводит сообщение вида
     *    > start at <дата и время запуска>
     *
     * @return void
     */
    protected function printStartDate()
    {
        $this->terminal->info("start at", $this->startDate);
    }

    /**
     * Выводит сообщение вида
     *    > iteration skipped at <дата и время пропуска итерации>
     *
     * @return void
     */
    protected function printSkip()
    {
        $this->terminal->warning("iteration skipped at", date('c'));
    }

    /**
     * Устанавливает время последнего запуска
     *    если передан параметр $lastRunDate будет установлено его значение,
     *    в противном случае - текущие дата и время
     *
     * @param string $date
     * @return void
     */
    protected function setLastRunDate(string $date = '')
    {
        $this->lastRunDate = $date ?: date('c');
    }
}