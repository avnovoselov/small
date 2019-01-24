<?php
/**
 * Created by PhpStorm.
 * User: Anatoliy Novoselov
 * Date: 22.01.2019
 * Time: 19:40
 */

namespace Small;

/**
 * Interface InterfaceDaemon
 * @package Small
 */
interface InterfaceDaemon
{
	/**
	 * Метод, реализующий итерацию демона
	 *
	 * @return void
	 */
	public function process();

	/**
	 * Проверяет удовлетворены ли все зависимости для выполнения итерации:
	 *  * подключение к SQL и NoSQL хранилищам
	 *  * доступность внешних сервисов
	 *  * наличие файлов
	 *  etc
	 *
	 * @return bool
	 *  true - пропустить итерацию,
	 *  false - итерацию не пропускать, все зависимости удовлетворены
	 */
	public function skip(): bool;

	/**
	 * Предустановки окружения
	 *  * настройки подключения к БД
	 *  * настройка отображения ошибок
	 *  etc
	 *
	 * @return InterfaceDaemon
	 */
	public function environment(): InterfaceDaemon;

	/**
	 * Запускает бесконечный цикл
	 *
	 * @return void
	 */
	public function run();
}