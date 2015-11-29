<?php

namespace Acewidger;

class Acewidger
{
    public $start;
    public $goal;
    public $graph;

    private $closed_set;
    private $open_set;
    private $came_from;
    private $score;

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

        $this->came_from  = [];
        $this->closed_set = [];
        $this->open_set   = [$start => true];
        $this->score      = [$start => 0];
    }

    private function runAStar()
    {
        while (!empty($this->open_set))
        {
            $current = $this->electCurrent();
            if ($current == $this->goal)
            {
                return $this->makeResult();
            }
            $this->markCurrentAsProcessed($current);
            $possibleDirections = $this->selectPossibleDirections($current);
            foreach ($possibleDirections as $direction) {
                $dest = $direction['to'];
                $this->open_set[$dest] = true;
                $tentative_score = $this->score[$current] + $direction['cost'];
                if ($this->isTentativeScoreBetter($tentative_score, $dest))
                {
                    $this->came_from[$dest] = $current;
                    $this->score[$dest] = $tentative_score;
                }
            }
        }

        throw new ImpossiblePathException;
    }

    private function electCurrent()
    {
        $min = INF;
        $current = NULL;
        foreach ($this->score as $key => $value)
        {
            if ($this->open_set[$key] && $value < $min)
            {
                $min = $value;
                $current = $key;
            }
        }
        return $current;
    }

    private function makeResult()
    {
        return [
            'path' => $this->reconstructPath(),
            'cost' => $this->score[$this->goal]
        ];
    }

    private function markCurrentAsProcessed($current)
    {
        unset($this->open_set[$current]);
        $this->closed_set[$current] = true;
    }

    private function selectPossibleDirections($current)
    {
        $result = [];
        foreach ($this->graph as $direction) {
            $from = $direction['from'];
            $to = $direction['to'];
            if (($from == $current) && !$this->closed_set[$to])
            {
                $result[] = $direction;
            }
        }
        return $result;
    }

    private function isTentativeScoreBetter($tentative_score, $dest)
    {
        return !$this->score[$dest] || ($tentative_score < $this->score[$dest]);
    }

    private function reconstructPath()
    {
        $current = $this->goal;
        $result = [$current];
        while ($this->came_from[$current]) {
            $current = $this->came_from[$current];
            array_unshift($result, $current);
        }
        return $result;
    }
}
