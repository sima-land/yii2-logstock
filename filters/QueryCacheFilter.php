<?php
namespace pastuhov\logstock\filters;

/**
 * Class for filtering yii's cache messages
 *
 * @package pastuhov\logstock\filters
 */
class QueryCacheFilter extends RegexpFilter
{
    /**
     * @var array
     */
    public $patterns = [
        '/(Saved query result in cache|Query result served from cache|Executing Redis Command: [A-Z]+)\n/',
    ];

    /**
     * @var array
     */
    public $replacement = [
        '',
    ];
}
