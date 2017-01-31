<?php
namespace pastuhov\logstock;

interface LogFilterInterface
{
    public function filter(string $log);
}