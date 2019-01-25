<?php
/**
 * Created by PhpStorm.
 * User: Anatoliy Novoselov
 * Date: 22.01.2019
 * Time: 19:43
 */

namespace Small;

/**
 * Class Daemon
 * @package Small
 */
abstract class AbstractDaemon implements InterfaceDaemon
{
	/**
	 * Имя демона
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Дата запуска демона в формате ISO-8601
	 *    <?php
	 *        ...
	 *        $this->startDate = date('c');
	 * @var string
	 */
	protected $startDate;

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
	public function environment(): InterfaceDaemon
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
	 * @see AbstractDaemon::name
	 *
	 * Задержка между итерациями
	 * @param int $sleep
	 * @see AbstractDaemon::$sleep
	 *
	 * Флаг, могут ли быть пропущенные итерации (напр.: отсутствие соединение с БД)
	 *
	 * @return InterfaceDaemon $this;
	 */
	public function __construct(string $name, int $sleep = 1)
	{
		$this->name = $name;
		$this->startDate = date('c');
		$this->sleep = $sleep;

		return $this;
	}

	/**
	 * Добавляет возможность получать аргументы, переданные при вызове скрипта
	 *
	 * аргументы по умолчанию
	 * @param array $defaultArguments
	 *
	 *    Напр.:
	 *    <?php
	 *    ...
	 *    $foo->arguments(["myparam" => 1]);
	 *
	 *    для скрипт, вызванного без параметров shell/> php foo.php
	 *        echo $this->args->get('myparam'); // 1
	 *
	 *    для скрипта shell/> php foo.php --myparam=3
	 *        echo $this->args->get('myparam'); // 3
	 *
	 * @return AbstractDaemon $this;
	 */
	final public function arguments(array $defaultArguments = [])
	{
		$this->arguments = new Arguments($defaultArguments);
		$this->arguments->process();

		return $this;
	}

	/**
	 * Запуск демона
	 *    реализует бесконечный цикл, в каждой итерации которого выполняет метод @see InterfaceDaemon::process
	 *
	 * @return void
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
	final protected function printName()
	{
		echo PHP_EOL . " Daemon: {$this->name}";
	}

	/**
	 * Выводит сообщение вида
	 *    > start at <дата и время запуска>
	 *
	 * @return void
	 */
	final protected function printStartDate()
	{
		echo PHP_EOL . " start at {$this->startDate}";
	}

	/**
	 * Выводит сообщение вида
	 *    > iteration skipped at <дата и время пропуска итерации>
	 *
	 * @return void
	 */
	protected function printSkip()
	{
		echo PHP_EOL . " iteration skipped at " . date('c');
	}

	/**
	 * Устанавливает время последнего запуска
	 *    если передан параметр $lastRunDate будет установлено его значение,
	 *    в противном случае - текущие дата и время
	 *
	 * @param string $lastRunDate
	 * @return void
	 */
	final protected function setLastRunDate(string $lastRunDate = '')
	{
		$this->lastRunDate = $lastRunDate ?: date('c');
	}
}