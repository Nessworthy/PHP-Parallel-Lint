<?php

namespace JakubOnderka\PhpParallelLint\Writer;

interface IWriter
{
    /**
     * @param string $string
     */
    public function write($string);
}
