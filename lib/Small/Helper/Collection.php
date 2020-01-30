<?php


namespace Small\Helper;


use \Exception;

class Collection
{
    /**
     * Перезаписывть элементы в случае возникновения конфликта
     * @see Collection::assoc
     */
    const ASSOC_ACTION_OVERWRITE = 1 << 0;

    /**
     * Игнорировать 2 и последующие элементы в случае возникновения конфликтов
     * @see Collection::assoc
     */
    const ASSOC_ACTION_IGNORE = 1 << 1;

    /**
     * Объединить элементы в массивы в случае возникновения конфликта
     * @see Collection::assoc
     */
    const ASSOC_ACTION_GROUP = 1 << 2;

    /**
     * Применить array_merge() в случае возникновения конфликта
     * @see Collection::assoc
     */
    const ASSOC_ACTION_MERGE = 1 << 3;

    /**
     * Вызваь Exception в случае возникновения конфликта
     * @see Collection::assoc
     */
    const ASSOC_ACTION_EXCEPTION = 1 << 4;

    /**
     * Приводит массив $collection к ассоциативному, в качестве ключей использует значение поля $uniqueField.
     * По-умолчанию используется поле "id", элементы с равными "id" будут перезаписаны.
     * Все элементы, у которых поле $uniqueField отсутствует будут проигнорированы.
     *
     * @param array $collection - массив
     * @param string $uniqueField - поле, которое используется в качетсве ключа ассоциативного массива
     * @param int $action - действие, которое будет выполнено в случае возникновения конфликтов (повторения $uniqueField)
     *
     * @return array - ассоиативный массив
     * @throws Exception только для $action == Collection::ASSOC_ACTION_EXCEPTION
     */
    final public static function assoc(array $collection, $uniqueField = 'id', int $action = Collection::ASSOC_ACTION_OVERWRITE)
    {
        $result = [];
        foreach ($collection as $value) {
            if (!isset($value[$uniqueField])) {
                continue;
            }

            switch ($action) {
                /**
                 * Игнорировать дубли. (Остается только первый элемент)
                 */
                case static::ASSOC_ACTION_IGNORE:
                    if (isset($result[$value[$uniqueField]])) {
                        continue;
                    } else {
                        $result[$value[$uniqueField]] = $value;
                    }
                    break;
                /**
                 * Группировать элеметы с одинаковым $uniqueField в массивы
                 */
                case static::ASSOC_ACTION_GROUP:
                    if (!isset($result[$value[$uniqueField]])) {
                        $result[$value[$uniqueField]] = [];
                    }
                    $result[$value[$uniqueField]][] = $value;
                    break;
                /**
                 * Применять к элементам с одинаковыми $uniqueField функцию array_merge
                 */
                case static::ASSOC_ACTION_MERGE:
                    if (isset($result[$value[$uniqueField]])) {
                        $result[$value[$uniqueField]] = array_merge($result[$value[$uniqueField]], $value);
                    } else {
                        $result[$value[$uniqueField]] = $value;
                    }
                    break;
                /**
                 * В случае возникновления конфликта будет вызван Exception
                 */
                case static::ASSOC_ACTION_EXCEPTION:
                    if (isset($result[$value[$uniqueField]])) {
                        throw new Exception('Duplicate elements');
                    } else {
                        $result[$value[$uniqueField]] = $value;
                    }
                    break;
                /**
                 * По умолчанию элементы будут перезаписаны
                 */
                case static::ASSOC_ACTION_OVERWRITE:
                default:
                    $result[$value[$uniqueField]] = $value;
                    break;
            }
        }

        return $result;
    }
}