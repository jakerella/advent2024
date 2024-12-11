<?php

namespace Advent;

use Advent\Logger;

class Day02
{   
    public function run($lines) {
        $p1_safe = 0;
        $p2_safe = 0;
        foreach ($lines as $line_num => $line) {
            $report = preg_split('/\s+/', $line);
            
            $safe = Day02::isSafe($report);
            if ($safe) {
                // Logger::log("safe: $line");
                $p1_safe++;
            }

            foreach ($report as $i => $num) {
                $dampened = $report;
                array_splice($dampened, $i, 1);
                $safe = Day02::isSafe($dampened);
                if ($safe) {
                    // Logger::log("p2 safe: $line");
                    $p2_safe++;
                    break;
                }
            }
        }

        Logger::log("Part 1: $p1_safe");

        Logger::log("Part 2: $p2_safe");
    }

    private static function isSafe($report) {
        $unsafe = false;
        $prev = 0;
        $isPos = true;
        foreach ($report as $i => $num) {
            $num = (int) $num;
            if ($i == 0) {
                $prev = $num;
                continue;
            }
            if ($num == $prev) {
                $unsafe = true;
                break;
            } else if ($i == 1) {
                $isPos = ($num > $prev) ? true : false;
            } else if (($isPos && ($num < $prev)) || (!$isPos && ($num > $prev))) {
                // Logger::log("wrong direction on $line_num (going from $prev to $num)");
                $unsafe = true;
                break;
            }
            if (abs($num - $prev) > 3) {
                // Logger::log("more than 3 diff on $line_num (going from $prev to $num)");
                $unsafe = true;
                break;
            }
            $prev = $num;
        }

        return !$unsafe;
    }
}