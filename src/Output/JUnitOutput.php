<?php

namespace JakubOnderka\PhpParallelLint\Output;

use JakubOnderka\PhpParallelLint\ErrorFormatter;
use JakubOnderka\PhpParallelLint\Result;
use JakubOnderka\PhpParallelLint\SyntaxError;
use JakubOnderka\PhpParallelLint\Writer\IWriter;

class JUnitOutput implements IOutput
{
    private $writer;

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
        $this->writer->write('<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
    }

    public function writeResult(Result $result, ErrorFormatter $errorFormatter, $ignoreFails)
    {
        $totalTests = \count($result->getCheckedFiles());

        $errors = [];

        foreach ($result->getErrors() as $error) {
            $message = $error->getMessage();
            if ($error instanceof SyntaxError) {
                $line = $error->getLine();
                $source = "Syntax Error";
            } else {
                $line = 1;
                $source = "Linter Error";
            }

            $errors[$error->getShortFilePath()][] = array(
                'message' => $message,
                'line' => $line,
                'source' => $source,
            );
        }

        foreach ($errors as $errorCollection) {
            $totalTests += 1 - \count($errorCollection);
        }

        $this->writer->write(sprintf(
            '<testsuites name="%s" tests="%s" failures="%s" time="%s">' . PHP_EOL,
            'PHP Parallel Lint Tests',
            $totalTests,
            $result->getErrorCount(),
            $result->getTestTime()
        ));

        foreach ($result->getCheckedFiles() as $checkedFile) {

            $errorCount = 0;

            $fileErrors = $errors[$checkedFile] ?? null;

            if ($fileErrors) {
                $errorCount = \count($errors[$checkedFile]);
            }

            $this->writer->write(sprintf(
                '<testsuite name="%s" tests="%d" failures="%d">' . PHP_EOL,
                $checkedFile,
                1,
                $errorCount,
                $result->getTestTime()
            ));

            if ($fileErrors) {
                foreach ($fileErrors as $error) {
                    $this->writer->write(sprintf(
                        '<testcase name="%s" file="%s">' . PHP_EOL,
                        'PHP Lint Check',
                        $checkedFile
                    ));

                    $this->writer->write(sprintf(
                        '<failure type="%s" message="%s"/>' . PHP_EOL,
                        $error['source'],
                        $error['message']
                    ));

                    $this->writer->write('</testcase>' . PHP_EOL);
                }
            } else {
                $this->writer->write(sprintf(
                    '<testcase name="%s" file="%s" />' . PHP_EOL,
                    'PHP Lint Check',
                    $checkedFile
                ));
            }


            $this->writer->write('</testsuite>' . PHP_EOL);
        }

        $this->writer->write('</testsuites>' . PHP_EOL);
    }
}