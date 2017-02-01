<?php
namespace pastuhov\logstock\filters;

/**
 * Class for filtering yii's session queries
 *
 * @package pastuhov\logstock\filters
 */
class DbSessionFilter extends RegexpFilter
{
    /**
     * @var array
     */
    public $patterns = [
        '/FROM [`"]:TABLE:["`] WHERE ["`]expire["`]>\d+ AND ["`]id["`]=\'\w+\'/',
        '/FROM [`"]:TABLE:["`] WHERE ["`]id["`]=\'\w+\'/',
        '/INTO [`"]:TABLE:["`](.*)VALUES \([^)]+\)/',
    ];

    /**
     * @var array
     */
    public $replacement = [
        'FROM :TABLE: WHERE "expire">:DYNAMIC "id"=:DYNAMIC',
        'FROM :TABLE: WHERE "id"=:DYNAMIC',
        'INTO :TABLE: $1VALUES (:DYNAMIC)',
    ];

    /**
     * @var array
     */
    public $placeholders = [
        ':TABLE:' => 'session'
    ];
}
