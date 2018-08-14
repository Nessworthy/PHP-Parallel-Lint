<?php

namespace JakubOnderka\PhpParallelLint\Writer;

class ConsoleWriter implements IWriter
{
    /**
     * @param string $string
     */
    public function write($string)
    {
        echo $string;
    }
}
