<?php

namespace Advent;

use Advent\Logger;

class Day18
{
    public function run($lines) {
        
        $max = 6;
        $bytes = [];
        $count = 12;
        foreach ($lines as $i => $line) {
            preg_match_all('/\d+/', $line, $nums);
            array_push($bytes, array_map(function($n) { return (int) $n; }, $nums[0]));
        }
        // Logger::log(json_encode($bytes));

        $map = array_fill(0, $max+1, array_fill(0, $max+1, "."));
        for ($i=0; $i<$count; $i++) {
            $map[$bytes[$i][1]][$bytes[$i][0]] = "#";
        }
        Day18::show_map($map);

        $p1 = 0;
        $start = [0,0];
        $end = [$max,$max];
        // TODO: Could use Manhatten dist? Or Dijkstra (or just basic graph nav)?

        Logger::log("Part 1: $p1");

        // Logger::log("Part 2: $p2");
    }

    private static function show_map($map) {
        foreach ($map as $y => $row) {
            Logger::log(join('', array_map(function ($s) { return $s[0]; }, $row)));
        }
    }
}