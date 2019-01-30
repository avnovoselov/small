<?php
/**
 * Created by PhpStorm.
 * User: avnovoselov
 * Date: 25.01.2019
 * Time: 17:55
 */

namespace Small;

/**
 * Class Terminal
 *  Работа с выводом в терминал
 * @package Small
 */
class Terminal
{
	// цвета текста
	const TEXT_COLOR_BLACK = '0;30';
	const TEXT_COLOR_BLUE = '0;34';
	const TEXT_COLOR_CYAN = '0;36';
	const TEXT_COLOR_GREEN = '0;32';
	const TEXT_COLOR_GREY = '1;30';
	const TEXT_COLOR_MAGENTA = '0;35';
	const TEXT_COLOR_RED = '0;31';
	const TEXT_COLOR_WHITE = '1;37';
	const TEXT_COLOR_YELLOW = '0;33';
	const TEXT_COLOR_LIGHT_BLUE = '1;34';
	const TEXT_COLOR_LIGHT_CYAN = '1;36';
	const TEXT_COLOR_LIGHT_GREEN = '1;32';
	const TEXT_COLOR_LIGHT_GREY = '0;37';
	const TEXT_COLOR_LIGHT_MAGENTA = '1;35';
	const TEXT_COLOR_LIGHT_RED = '1;31';
	const TEXT_COLOR_LIGHT_YELLOW = '1;33';

	// цвета фона
	const TEXT_BACKGROUND_BLACK = '40';
	const TEXT_BACKGROUND_BLUE = '44';
	const TEXT_BACKGROUND_CYAN = '46';
	const TEXT_BACKGROUND_GREEN = '42';
	const TEXT_BACKGROUND_LIGHT_GREY = '47';
	const TEXT_BACKGROUND_MAGENTA = '45';
	const TEXT_BACKGROUND_RED = '41';
	const TEXT_BACKGROUND_YELLOW = '43';

	// тип анимации загрузчика
	const TYPE_LOADING_PERCENT = 1 << 0;
	const TYPE_LOADING_BAR = 1 << 1;
	const TYPE_LOADING_ROTATE = 1 << 2;
	const TYPE_LOADING_PULSE = 1 << 3;

	/**
	 * Размер смещения от левого края экрана
	 *
	 * @var int
	 */
	protected $shift = 0;

	/**
	 * Символ смещения
	 *
	 * @var string
	 */
	protected $shiftSymbol = '  ';

	/**
	 * Размер полосы загрузки (в символах)
	 *
	 * @var int
	 */
	protected $loadingBarSize = 20;

	/**
	 * Размер пульса загрузки (в символах)
	 *  от 0 до 100
	 *
	 * @var int
	 */
	protected $loadingPulseSize = 20;

	/**
	 * Код символа для bar и pulse
	 *
	 * @var int
	 */
	protected $loadingSymbol = '*';

	/**
	 * Последовательность символов анимации
	 *
	 * @var array
	 */
	protected $loadingSymbolRotate = ['/', '-', '\\', '|'];

	/**
	 * Длина разделителя
	 *
	 * @var int
	 */
	protected $separatorLength = 50;

	/**
	 * Символ разделителя
	 *
	 * @var string
	 */
	protected $separatorSymbol = '-';

	/**
	 * Выводим сообщение
	 *
	 * @param string $message - сообщение
	 * @param string $color - цвет текста
	 * @param string $background - цвет фона
	 *
	 * @return Terminal
	 */
	public function print(string $message, string $color, string $background = '')
	{
		echo PHP_EOL . $this->messageWrapper($message, $color, $background, false);

		return $this;
	}

	/**
	 * Возвращает цветовой код
	 *
	 * @param string $color
	 * @param string $background
	 *
	 * @return string
	 */
	protected function colorCode(string $color, string $background = '')
	{
		if (getenv('TERM') === 'xterm') {
			return "\e[$color" . ($background ? ";{$background}" : '') . 'm';
		}
		return '';
	}

	/**
	 * Добавляет ansi коды с установками цвета текста, отступы к сообщению
	 *
	 * @param string $message - сообщение
	 * @param string $color - цвет текста
	 * @param string $background - цвет фона
	 * @param bool $clear - очистка строки
	 *
	 * @return string
	 */
	protected function messageWrapper(string $message, string $color = '', string $background = '', bool $clear = false)
	{
		$color = $color ?: static::TEXT_COLOR_LIGHT_GREY;

		return ($clear ? "\r" : "") .
			str_repeat($this->shiftSymbol, $this->shift) .
			$this->colorCode($color, $background) .
			$message .
			$this->colorCode(static::TEXT_COLOR_LIGHT_GREY, static::TEXT_BACKGROUND_BLACK);
	}

	/**
	 * @param string ...$messages
	 *
	 * @return Terminal
	 */
	public function info(...$messages)
	{
		$this->print(implode(' ', $messages), static::TEXT_COLOR_LIGHT_GREY);

		return $this;
	}

	/**
	 * @param string ...$messages
	 *
	 * @return Terminal
	 */
	public function success(...$messages)
	{
		$this->print(implode(' ', $messages), static::TEXT_COLOR_GREEN);

		return $this;
	}

	/**
	 * @param string ...$messages
	 *
	 * @return Terminal
	 */
	public function warning(...$messages)
	{
		$this->print(implode(' ', $messages), static::TEXT_COLOR_YELLOW);

		return $this;
	}

	/**
	 * @param string ...$messages
	 *
	 * @return Terminal
	 */
	public function danger(...$messages)
	{
		$this->print(implode(' ', $messages), static::TEXT_COLOR_RED);

		return $this;
	}

	/**
	 * @param string ...$messages
	 *
	 * @return Terminal
	 */
	public function critical(...$messages)
	{
		$this->print(mb_strtoupper(implode(' ', $messages), 'UTF-8'), static::TEXT_COLOR_BLACK, static::TEXT_BACKGROUND_RED);

		return $this;
	}

	/**
	 * @param string $messages
	 * @param string $color
	 * @return $this
	 */
	public function header(string $messages, string $color = '')
	{
		$this->print(mb_strtoupper($messages, 'UTF-8'), $color);

		return $this;
	}

	/**
	 * Выводит пустую строку
	 *
	 * @return Terminal
	 */
	public function blank()
	{
		$this->print('', '');

		return $this;
	}

	/**
	 * Выводит разделитель
	 *
	 * @return Terminal
	 */
	public function separator()
	{
		$this->print(str_repeat($this->separatorSymbol, $this->separatorLength), '');

		return $this;
	}

	/**
	 * Увеличить смещение
	 *
	 * @return Terminal
	 */
	public function shift()
	{
		$this->shift = min(5, $this->shift + 1);

		return $this;
	}

	/**
	 * Уменьшить смещение
	 *
	 * @return Terminal
	 */
	public function unshift()
	{
		$this->shift = max(0, $this->shift - 1);

		return $this;
	}

	/**
	 * Обнуляет смещение
	 *
	 * @return $this
	 */
	public function resetShift()
	{
		$this->shift = 0;

		return $this;
	}

	/**
	 * Вывод c очищение строки
	 *
	 * @param string $message - сообщение
	 * @param string $color - цвет текста
	 * @param string $background - цвет фона
	 *
	 * @return void
	 */
	public function clearPrint(string $message, string $color = '', string $background = '')
	{
		echo $this->messageWrapper("{$message}", $color, $background, true);
	}

	/**
	 * Вывод статуса прогресса
	 *
	 * @param float $percent - %-выполнения процесса
	 * @param string $type - тип (TYPE_LOADING_BAR, TYPE_LOADING_ROTATE, TYPE_LOADING_PERCENT)
	 * @param string $color - цвет текста
	 *
	 * @return void
	 */
	public function loading(float $percent, string $type = '', string $color = '')
	{
		$color = $color ?: static::TEXT_COLOR_GREEN;
		$percent = min(100, max($percent, 0));

		switch ($type) {
			// вывод loading bar
			case static::TYPE_LOADING_BAR:
				$symbolCount = floor($this->loadingBarSize / 100 * $percent);

				$message = str_repeat($this->loadingSymbol, $symbolCount);
				$message .= str_repeat(' ', $this->loadingBarSize - $symbolCount);
				$message .= " {$percent}%";
				break;

			// вывод spinner
			case static::TYPE_LOADING_ROTATE:
				$message = $this->loadingSymbolRotate[round($percent) % count($this->loadingSymbolRotate)];
				break;

			// вывод pulse
			case static::TYPE_LOADING_PULSE:
				// направление движения
				$front = $percent % ($this->loadingPulseSize * 2) - $this->loadingPulseSize < 0;
				// позиция
				$position = ($front ? $percent % $this->loadingPulseSize : $this->loadingPulseSize - $percent % $this->loadingPulseSize);
				// строка загрузки
				$message = str_repeat(' ', $position) . $this->loadingSymbol . str_repeat(' ', $this->loadingPulseSize - $position);
				break;

			// вывод %
			default:
				$message = "{$percent}%";
				break;
		}

		$this->clearPrint($message, $color);
	}

	/**
	 * Terminal constructor.
	 * @param array $vars
	 *
	 * @return Terminal
	 */
	public function __construct(array $vars = [])
	{
		$properties = get_class_vars(get_class($this));

		foreach ($vars as $key => $val) {
			if (isset($properties[$key])) {
				$this->$key = $val;
			}
		}

		return $this;
	}
}