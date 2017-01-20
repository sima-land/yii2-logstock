<?php

namespace tests\models;

use Yii;

/**
 * @inheritdoc
 */
class PgStatStatements extends \simaland\pgstatstatements\models\PgStatStatements
{
    /**
     * @inheritdoc
     */
    public static function reset()
    {
        \Yii::$app->db->createCommand('DELETE FROM pg_stat_statements WHERE userid > 0')->execute();
    }
}
