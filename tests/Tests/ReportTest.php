<?php

namespace Tests;

use App\Report;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testA()
    {
        $report = new Report();
        $report->loadFromCsv('missingFile');
    }
}
