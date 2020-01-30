<?php


namespace Small;


/**
 * Class Arguments
 * @package Small
 */
class Arguments
{
    /**
     * Хранилище значений аргументов
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * Возвращает значение аргумента
     *
     * @param string $name
     * @return mixed|null
     */
    final public function get(string $name)
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * Args constructor.
     * @param array $defaultArguments
     *
     * @return Arguments $this;
     */
    final public function __construct(array $defaultArguments = [])
    {
        $this->arguments = $defaultArguments;

        // собираем параметр $longopts для функции getopt($shortopts, $longopts)
        // приводим ключи массива значений аргументов по умолчанию к виду ["<key>":: => <defaultValue>]
        // :: - указывает на необязательный аргумент
        $_arguments = array_map(function ($el) {
            return "{$el}::";
        }, array_keys($defaultArguments));

        $option = getopt("", $_arguments);

        // перезаписываем значения по умолчанию переданными аргументами
        $this->arguments = array_merge($defaultArguments, $option);

        return $this;
    }
}