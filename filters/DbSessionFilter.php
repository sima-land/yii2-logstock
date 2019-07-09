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
        '/WITH [`"]:EXCLUDED:["`] (`data`, `id`, `expire`) AS \(VALUES \(\'__flash|.+\', \'[a-z0-9]{26}\', \d{10}\)/',
    ];

    /**
     * @var array
     */
    public $replacement = [
        'FROM :TABLE: WHERE "expire">:DYNAMIC "id"=:DYNAMIC',
        'FROM :TABLE: WHERE "id"=:DYNAMIC',
        'INTO :TABLE: $1VALUES (:DYNAMIC)',
        'WITH ":EXCLUDED:" (`data`, `id`, `expire`) AS (VALUES (:SESSIONDATA, :DYNAMIC, :TIMESTAMP)',
    ];

    /**
     * @var array
     */
    public $placeholders = [
        ':TABLE:' => 'session',
        ':EXCLUDED:' => 'EXCLUDED'
    ];
}
