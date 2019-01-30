<?php
/**
 * Created by PhpStorm.
 * User: avnovoselov
 * Date: 25.01.2019
 * Time: 10:01
 */

namespace Small;


abstract class Script
{
	/**
	 * Имя скрипта
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Дата и время запуска скрипта ISO-8601
	 *
	 * @var string
	 */
	protected $startDate;

	/**
	 * @var Arguments
	 */
	protected $arguments;

	/**
	 * @var Terminal;
	 */
	protected $terminal;

	/**
	 * Возвращает время работы скрипта
	 *
	 * @return int
	 */
	protected function getWorkTime()
	{
		return time() - strtotime($this->startDate);
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
	 *        echo $this->arguments->get('myparam'); // 1
	 *
	 *    для скрипта shell/> php foo.php --myparam=3
	 *        echo $this->arguments->get('myparam'); // 3
	 *
	 * @return static $this;
	 */
	final public function arguments(array $defaultArguments = [])
	{
		$this->arguments = new Arguments($defaultArguments);

		return $this;
	}

	/**
	 * AbstractScript constructor.
	 * @param string $name - Имя скрипта
	 *
	 * @return static $this;
	 */
	public function __construct(string $name)
	{
		$this->startDate = date('c');
		$this->name = $name;

		$this->terminal = new Terminal();

		return $this;
	}
}