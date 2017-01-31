<?php

namespace pastuhov\logstock\tests;

use pastuhov\logstock\LogFilterInterface;

class ExampleFilter implements LogFilterInterface
{
    public function filter($log)
    {
        return preg_replace(
            "/(session_id|expired_at|updated_at) [=<>] '[^']+'/",
            '$1 = :DYNAMIC',
            $log
        );
    }
}
