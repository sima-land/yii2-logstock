<?php
namespace pastuhov\logstock\filters;

use pastuhov\logstock\LogFilterInterface;

/**
 * Class for sorting values in "IN" operator
 *
 * @package pastuhov\logstock\filters
 */
class WhereInSortingFilter implements LogFilterInterface
{
    /**
     * @inheritdoc
     */
    public function filter($log)
    {
        return preg_replace_callback('/\s+IN\s+\(([^\)]+)\)/', function ($matches) {
            $values = array_map('trim', explode(',', $matches[1]));
            sort($values);
            return str_replace($matches[1], implode(', ', $values), $matches[0]);
        }, $log);
    }
}
