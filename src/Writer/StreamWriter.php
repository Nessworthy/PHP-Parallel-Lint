<?php

namespace JakubOnderka\PhpParallelLint\Writer;

class StreamWriter implements IWriter
{
    /** @var resource */
    protected $stream;

    public function __construct($writableStream)
    {
        $this->stream = $writableStream;
    }

    public function write($string)
    {
        fwrite($this->stream, $string);
    }
}
