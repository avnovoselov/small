<?php
/**
 * Created by PhpStorm.
 * User: avnovoselov
 * Date: 06.02.2019
 * Time: 19:10
 */

namespace Small;

/**
 * Class Path
 * @package Small
 */
class Path
{
	/**
	 * @var string
	 */
	protected $path = '';

	/**
	 * @param string ...$arguments
	 * @return string
	 */
	public static function concatenation(string ...$arguments)
	{
		foreach ($arguments as $key => $val) {
			if (!$key) {
				$arguments[$key] = rtrim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $val), DIRECTORY_SEPARATOR);
			} elseif ($key == count($arguments) - 1) {
				$arguments[$key] = ltrim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $val), DIRECTORY_SEPARATOR);
			} else {
				$arguments[$key] = trim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $val), DIRECTORY_SEPARATOR);
			}
		}

		return implode(DIRECTORY_SEPARATOR, $arguments);
	}

	/**
	 * Path constructor.
	 * @param string ...$arguments
	 */
	public function __construct(string ...$arguments)
	{
		$this->path = call_user_func_array(['static', 'concatenation'], $arguments);

		return $this;
	}

	/**
	 * @param string ...$parts
	 * @return $this
	 */
	public function add(string ... $parts)
	{
		array_unshift($parts, $this->path);
		$this->path = call_user_func_array(['static', 'concatenation'], $parts);

		return $this;
	}

	/**
	 * @param string ...$parts
	 * @return Path
	 */
	public function path(string ... $parts)
	{
		return call_user_func_array([clone $this, 'add'], $parts);
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return (string)$this;
	}

	/**
	 * @return String
	 */
	public function __toString(): string
	{
		return $this->path;
	}
}