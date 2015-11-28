<?php

namespace Tests\Acewidger;

use Acewidger\Acewidger;

class AcewidgerTest extends \PHPUnit_Framework_TestCase
{
    public function testCalc()
    {
        $result = Acewidger::calc('A', 'B', [
            ['from' => 'A', 'to' => 'B', 'cost' => 11]
        ]);
        $this->assertEquals(['path' => ['A', 'B'], 'cost' => 11], $result);
    }
}
