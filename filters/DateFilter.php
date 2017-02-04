<?php
namespace pastuhov\logstock\filters;

/**
 * Class for filtering dates in ISO 8601 format (date('c'))
 *
 * @package pastuhov\logstock\filters
 */
class DateFilter extends RegexpFilter
{
    /**
     * @var array
     */
    public $patterns = [
        '/\'\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\+\d{2}:\d{2}\'/',
    ];

    /**
     * @var array
     */
    public $replacement = [
        ':DATE',
    ];
}
