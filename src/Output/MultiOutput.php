<?php
namespace JakubOnderka\PhpParallelLint\Output;

use JakubOnderka\PhpParallelLint\ErrorFormatter;
use JakubOnderka\PhpParallelLint\Result;

class MultiOutput implements IOutput
{
    private $outputs;

    public function __construct(IOutput ...$outputs)
    {
        $this->outputs = $outputs;
    }

    private function runOnAll($methodName, array $arguments = [])
    {
        foreach ($this->outputs as $output) {
            \call_user_func_array([$output, $methodName], $arguments);
        }
    }

    public function ok()
    {
        $this->runOnAll('ok');
    }

    public function skip()
    {
        $this->runOnAll('skip');
    }

    public function error()
    {
        $this->runOnAll('error');
    }

    public function fail()
    {
        $this->runOnAll('fail');
    }

    public function setTotalFileCount($count)
    {
        $this->runOnAll('setTotalFileCount', [$count]);
    }

    public function writeHeader($phpVersion, $parallelJobs, $hhvmVersion = null)
    {
        $this->runOnAll('writeHeader', [$phpVersion, $parallelJobs, $hhvmVersion]);
    }

    public function writeResult(Result $result, ErrorFormatter $errorFormatter, $ignoreFails)
    {
        $this->runOnAll('writeResult', [$result, $errorFormatter, $ignoreFails]);
    }
}
