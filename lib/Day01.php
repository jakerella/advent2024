<?php

namespace Advent;

use Advent\Logger;

class Day01
{   
    public function run($input) {
        Logger::log("Day 01 Start");

        $list_one = [];
        $list_two = [];
        $lines = file($input);
        foreach ($lines as $line_num => $line) {
            // Logger::log($line);
            $numbers = preg_split('/\s+/', $line);
            array_push($list_one, $numbers[0]);
            array_push($list_two, $numbers[1]);
        }

        sort($list_one);
        sort($list_two);

        $distance = 0;
        foreach ($list_one as $i => $num) {
            $diff = abs($num - $list_two[$i]);
            $distance += $diff;
            // Logger::log("$num - {$list_two[$i]} = $diff");
        }
        Logger::log("Part 1, total difference: $distance");

        // Part 2
        $similarity = 0;
        foreach ($list_one as $i => $num) {
            $found = array_keys($list_two, $num);
            $score = $num * count($found);
            $similarity += $score;
            // Logger::log("$num has similarity score: $score");
        }
        Logger::log("Part 2, similarity score: $similarity");
    }
}