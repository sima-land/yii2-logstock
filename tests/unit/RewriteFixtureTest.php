<?php

namespace pastuhov\logstock\tests\unit;

use pastuhov\logstock\Module;
use Yii;

class RewriteFixtureTest extends UnitTestCase
{
    public function testRewriteFixture()
    {
        /** @var Module $module */
        $module = \Yii::$app->getModule('logstock');
        $pathToFixture = \Yii::getAlias($module->fixturePath) . '/testLogstockFixture..log';
        if (file_exists($pathToFixture)) {
            unlink($pathToFixture);
        }
        $this->assertFileNotExists($pathToFixture);
        $command = __DIR__ . '/../../vendor/bin/codecept run unit RewriteFixtureTest:testLogstockFixture';
        // Fixtures created
        exec($command, $output,  $code);
        $this->assertNotEquals($code, 0);
        $this->assertContains('Fixture has aggregated', implode('', $output));
        $this->assertFileExists($pathToFixture);

        // All is ok
        exec($command, $output,  $code);
        $this->assertEquals($code, 0);

        // Recreate fixtures
        exec($command . ' --env=rewrite', $output,  $code);
        $this->assertNotEquals($code, 0);
        $this->assertContains('Fixture has aggregated', implode('', $output));

        // All is ok again
        exec($command, $output,  $code);
        $this->assertEquals($code, 0);
    }

    public function testLogstockFixture()
    {
        $this->tester->assertLog(function () {
            Yii::info('Test info message');
            Yii::$app->getDb()->createCommand('SELECT * FROM page')->execute();
        }, Yii::$app);
    }
}
