<?php
namespace pastuhov\logstock\filters;

use pastuhov\logstock\LogFilterInterface;

/**
 * Class for filtering yii's session queries
 *
 * @package pastuhov\logstock\filters
 */
class YiiSessionFilter implements LogFilterInterface
{
    /**
     * @var string
     */
    public $sessionTableName = 'session';

    /**
     * @param string $log
     * @return string
     */
    public function filter($log)
    {
        $patterns = [
            sprintf('/FROM "%s" WHERE "expire">\d+ AND "id"=\'\w+\'/', $this->sessionTableName),
            sprintf('/FROM "%s" WHERE "id"=\'\w+\'/', $this->sessionTableName),
            sprintf('/INTO "%s".*VALUES \([^)]+\)/', $this->sessionTableName),
        ];
        $replacement = [
            sprintf('FROM "%s" WHERE "expire">:DYNAMIC "id"=:DYNAMIC', $this->sessionTableName),
            sprintf('FROM "%s" WHERE "id"=:DYNAMIC', $this->sessionTableName),
            sprintf('INTO "%s".*VALUES (:DYNAMIC)', $this->sessionTableName)
        ];

        return preg_replace($patterns, $replacement, $log);
    }
}
