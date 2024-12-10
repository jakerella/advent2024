<?php

namespace Advent;

use Advent\Logger;

class Day10
{   
    public function run($input) {
        Logger::log("Day 10 Start");
        $lines = file($input);

        $map = [];
        foreach ($lines as $line_num => $line) {
            $nums = array_map(function($s) { return (int) $s; }, str_split($line));
            array_pop($nums);
            array_push($map, $nums);
        }

        $p1 = 0;
        $p2 = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $spot) {
                if ($spot === 0) {
                    $found = Day10::find_next($map, $y, $x, 1, []);
                    $c = count($found);
                    $p1 += $c;

                    $p2 += Day10::find_distinct($map, $y, $x, 1);
                }
            }
        }
        
        Logger::log("Part 1: $p1");

        Logger::log("Part 2: $p2");
    }

    private static function find_next($map, $y, $x, $next_level, $found) {
        if ($next_level === 10) {
            if (array_search("$y,$x", $found) === false) {
                array_push($found, "$y,$x");
            }
            return $found;
        }
        $new_starts = [[$y-1,$x], [$y+1,$x], [$y,$x-1], [$y,$x+1]];
        $new_found = [];
        foreach ($new_starts as $i => $start) {
            if ($map[$start[0]][$start[1]] === $next_level) {
                $routes = Day10::find_next($map, $start[0], $start[1], $next_level+1, $found);
                foreach ($routes as $i => $f) {
                    if (array_search($f, $new_found) === false) {
                        array_push($new_found, $f);
                    }
                }
            }
        }

        return $new_found;
    }

    private static function find_distinct($map, $y, $x, $next_level) {
        if ($next_level === 10) {
            return 1;
        }
        $new_starts = [[$y-1,$x], [$y+1,$x], [$y,$x-1], [$y,$x+1]];
        $count = 0;
        foreach ($new_starts as $i => $start) {
            if ($map[$start[0]][$start[1]] === $next_level) {
                $count += Day10::find_distinct($map, $start[0], $start[1], $next_level+1);
            }
        }

        return $count;
    }
}