<?php

namespace yiiunit\extensions\debug;

use Yii;
use yii\debug\LogTarget;
use yii\debug\Module;

class LogTargetTest extends \Codeception\Test\Unit
{
    public function testGetRequestTime()
    {

        $logTarget = new LogTarget($module);
        $data = $this->invoke($logTarget, 'collectSummary');
        self::assertSame($_SERVER['REQUEST_TIME_FLOAT'], $data['time']);
    }
}
