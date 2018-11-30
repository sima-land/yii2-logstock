<?php
namespace pastuhov\logstock;

class AcceptanceHelper extends BaseHelper
{

    /**
     * @param callable $function
     * @param \Codeception\Actor $actor
     * @param string $identifier
     * @param array $filters
     */
    public function assertLog($function, $actor, $identifier='', $filters = [])
    {
        $actor->setHeader('Logstock', 'true');
        if ($filters) {
            $actor->setHeader('Logstock-filters', serialize($filters));
        }
        if ($this->config['logstock-rewrite']) {
            $actor->setHeader('Logstock-rewrite', 'true');
        }
        call_user_func($function);

        $actor->deleteHeader('Logstock');

        $fixtureFileName = $this->getFileName($identifier);
        $actor->setHeader('Logstock-Get-Content', base64_encode($fixtureFileName));
        $actor->amOnPage('logstock-agregate');
        $expected = base64_decode($actor->grabTextFrom('#expected'));

        $actor->deleteHeader('Logstock-filters');
        $actor->deleteHeader('Logstock-Get-Content');
        $actor->deleteHeader('Logstock-rewrite');

        if ($expected === '') {
            $this->logstockCreateFail();
        } else {
            $actual = base64_decode($actor->grabTextFrom('#actual'));

            $this->assertSame(
                $expected,
                $actual
            );
        }

    }

}
