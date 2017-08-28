<?php

namespace Tests;

use App\Item;
use PHPUnit\Framework\TestCase;
use App\Process;

class ProcessTest extends TestCase
{
    public function testDo()
    {
        $result = Process::do();
        $this->assertTrue($result);
    }

    public function testCreateRenamesEmpty()
    {
        $result = Process::createRenames([]);
        $this->assertSame([], $result);
    }

    public function testCreateRenames()
    {
        $line = [
            '17.08.2017 05.18.03',
            'Object (file) detected',
            '\\synology\Share\warehouse\_virtualcd\PowerISO 4.7\keygen.exe//PECompact',
            '\\synology\Share\warehouse\_virtualcd\PowerISO 4.7\keygen.exe//PECompact',
            'UDS:DangerousObject.Multi.Generic',
        ];
        $item = new Item($line);
        $result = Process::createRenames([$item]);
        $expected = 'mv "/synology/Share/warehouse/_virtualcd/PowerISO 4.7/keygen.exe" "/synology/Share/warehouse/_virtualcd/PowerISO 4.7/keygen(DangerousObject.Multi.Generic).virus.exe"';
        $this->assertSame([$expected], $result);
    }
}
