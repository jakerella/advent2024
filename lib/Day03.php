<?php

namespace Advent;

use Advent\Logger;

class Day03
{   
    public function run($input) {
        Logger::log("Day 03 Start");
        $lines = file($input);

        $p1_sum = 0;
        foreach ($lines as $line_num => $line) {
            preg_match_all('/mul\(\d+,\d+\)/', $line, $matches);
            foreach ($matches[0] as $i => $cmd) {
                preg_match_all('/\d+/', $cmd, $nums);
                $p1_sum += (int) $nums[0][0] * (int) $nums[0][1];
            }
        }

        Logger::log("Part 1: $p1_sum");

        $enabled = true;
        $p2_sum = 0;
        foreach ($lines as $line_num => $line) {
            preg_match_all('/(do\(\)|don\'t\(\)|mul\(\d+,\d+\))/', $line, $matches);
            foreach ($matches[1] as $i => $cmd) {
                // var_dump($cmd);
                if ($cmd == "don't()") {
                    $enabled = false;
                } else if ($cmd == "do()") {
                    $enabled = true;
                } else if ($enabled) {
                    preg_match_all('/\d+/', $cmd, $nums);
                    $p2_sum += (int) $nums[0][0] * (int) $nums[0][1];
                }
            }
        }

        Logger::log("Part 2: $p2_sum");
    }

}