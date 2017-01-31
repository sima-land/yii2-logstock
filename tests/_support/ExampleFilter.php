<?php

namespace pastuhov\logstock\tests;

use pastuhov\logstock\LogFilterInterface;

class ExampleFilter implements LogFilterInterface
{
    public function filter($log)
    {
        return str_replace('SELECT', 'UPDATE', $log);
    }
}
