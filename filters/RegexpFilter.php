<?php
namespace pastuhov\logstock\filters;

use pastuhov\logstock\LogFilterInterface;

/**
 * Class for filtering logs by regular expressions
 *
 * @package pastuhov\logstock\filters
 */
class RegexpFilter implements LogFilterInterface
{
    /**
     * @var string[]
     */
    public $patterns = [];

    /**
     * @var string[]
     */
    public $replacement = [];

    /**
     * @var array
     */
    public $placeholders = [];

    /**
     * @param string $log
     * @return string
     */
    public function filter($log)
    {
        foreach ($this->patterns as $key => $pattern) {
            $log = preg_replace(
                strtr($pattern, $this->placeholders),
                strtr($this->replacement[$key], $this->placeholders),
                $log
            );
        }

        return $log;
    }
}
