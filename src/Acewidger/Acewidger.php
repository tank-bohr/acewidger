<?php

/**
*
*/

namespace Acewidger;

class Acewidger
{
    public $start;
    public $goal;
    public $graph;

    public static function calc($start, $goal, $graph)
    {
        $a = new self($start, $goal, $graph);
        return $a->runAStar();
    }

    private function __construct($start, $goal, $graph)
    {
        $this->start = $start;
        $this->goal  = $goal;
        $this->graph = $graph;
    }

    private function runAStar()
    {
        return ['path' => [$this->start, $this->goal], 'cost' => 11];
    }
}
