<?php
/**
 * Created by PhpStorm.
 * User: avnovoselov
 * Date: 22.01.2019
 * Time: 20:06
 */

/**
 * Class Counter
 */
class Counter extends \Small\Daemon
{
	private $count = 0;

	/**
	 * Выводит Дату и время и кол-во итераций
	 *
	 * @inheritdoc
	 */
	public function process()
	{
		++$this->count;
		$this->terminal->info("iteration run at:", $this->lastRunDate);
		$this->terminal->info("iteration number:", $this->count);
		$this->terminal->info("work", $this->getWorkTime(), "sec");
	}

	/**
	 * Пропускаем итераию,
	 *  если итерация началась в нечетную секунду если daemon запущен с параметром --skip=even
	 *  если итерация началась в четную секунду если daemon запущен с параметром --skip=odd
	 *
	 * @inheritdoc
	 */
	public function skip(): bool
	{
		return $this->arguments->get('skip') == 'odd' ? time() % 2 : !(time() % 2);
	}

	/**
	 * @inheritdoc
	 * @return \Small\Daemon
	 */
	public function environment(): \Small\DaemonInterface
	{
		return $this;
	}
}