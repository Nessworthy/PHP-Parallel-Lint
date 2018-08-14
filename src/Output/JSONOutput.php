<?php

namespace JakubOnderka\PhpParallelLint\Output;

use JakubOnderka\PhpParallelLint\ErrorFormatter;
use JakubOnderka\PhpParallelLint\Result;
use JakubOnderka\PhpParallelLint\Writer\IWriter;

class JSONOutput implements IOutput
{
    /** @var IWriter */
    protected $writer;

    /** @var int */
    protected $phpVersion;

    /** @var int */
    protected $parallelJobs;

    /** @var string */
    protected $hhvmVersion;

    /**
     * @param IWriter $writer
     */
    public function __construct(IWriter $writer)
    {
        $this->writer = $writer;
    }

    public function ok()
    {

    }

    public function skip()
    {

    }

    public function error()
    {

    }

    public function fail()
    {

    }

    public function setTotalFileCount($count)
    {

    }

    public function writeHeader($phpVersion, $parallelJobs, $hhvmVersion = null)
    {
        $this->phpVersion = $phpVersion;
        $this->parallelJobs = $parallelJobs;
        $this->hhvmVersion = $hhvmVersion;
    }

    public function writeResult(Result $result, ErrorFormatter $errorFormatter, $ignoreFails)
    {
        echo json_encode(array(
            'phpVersion' => $this->phpVersion,
            'hhvmVersion' => $this->hhvmVersion,
            'parallelJobs' => $this->parallelJobs,
            'results' => $result,
        ));
    }
}