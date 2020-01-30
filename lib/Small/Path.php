<?php


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
     * @param Path $from
     * @param Path $to
     * @param int $mode
     * @return bool
     */
    public static function copy(Path $from, Path $to, int $mode = 0777)
    {
        if (!file_exists($from->getPath())) {
            return false;
        }

        mkdir($to->dir(), $mode, true);

        return copy($from->getPath(), $to->getPath());
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
     * @return String
     */
    public function __toString(): string
    {
        return $this->path;
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
     * @return array
     */
    final public function parts()
    {
        return explode(DIRECTORY_SEPARATOR, $this->path);
    }

    /**
     * @return string
     */
    public function dir()
    {
        $parts = $this->parts();

        return implode(DIRECTORY_SEPARATOR, array_slice($parts, 0, count($parts) - 1));
    }
}