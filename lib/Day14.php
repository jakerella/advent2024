<?php

namespace Advent;

use Advent\Logger;

class Day14
{   
    public function run($lines) {
        $robots = [];
        foreach ($lines as $i => $line) {
            preg_match_all('/\-?\d+/', $line, $nums);
            $nums = array_map(function($n) { return (int) $n; }, $nums[0]);
            array_push($robots, array(
                "p"=>[$nums[0], $nums[1]],
                "v"=>[$nums[2], $nums[3]]
            ));

        }
        // Logger::log(json_encode($robots));

        $max_x = 101;  // 11
        $max_y = 103;  // 7
        $time = 100000;
        for ($s=0; $s<$time; $s++) {
            foreach ($robots as $i => $r) {
                $x = $r["p"][0] + $r["v"][0];
                $y = $r["p"][1] + $r["v"][1];
                if ($x < 0) {
                    $x += $max_x;
                } else if ($x >= $max_x) {
                    $x -= $max_x;
                }
                if ($y < 0) {
                    $y += $max_y;
                } else if ($y >= $max_y) {
                    $y -= $max_y;
                }
                $robots[$i]["p"][0] = $x;
                $robots[$i]["p"][1] = $y;
            }

            $safety = Day14::get_safety_factor($robots, $max_x, $max_y);
            if ($safety < 50000000) {
                Logger::log("AFTER $s seconds, SAFETY FACTOR is $safety...");
                Day14::show_map($robots, $max_x, $max_y);
                sleep(1);
            }
        }
        // Logger::log(json_encode($robots));
        
        $p1 = Day14::get_safety_factor($robots, $max_x, $max_y);
        Logger::log("Part 1: $p1");

        // Logger::log("Part 2: $p2");
    }

    private static function get_safety_factor($robots, $max_x, $max_y) {
        $q1 = 0;
        $q2 = 0;
        $q3 = 0;
        $q4 = 0;
        foreach ($robots as $i => $r) {
            if ($r["p"][0] < floor($max_x / 2)) {
                if ($r["p"][1] < floor($max_y / 2)) {
                    $q1++;
                } else if ($r["p"][1] > floor($max_y / 2)) {
                    $q3++;
                }
            } else if ($r["p"][0] > floor($max_x / 2)) {
                if ($r["p"][1] < floor($max_y / 2)) {
                    $q2++;
                } else if ($r["p"][1] > floor($max_y / 2)) {
                    $q4++;
                }
            }
        }

        return $q1 * $q2 * $q3 * $q4;
    }

    private static function show_map($robots, $max_x, $max_y) {
        $map = [];
        for ($y=0; $y<$max_y; $y++) {
            $map[$y] = array_fill(0, $max_x, 0);
        }
        foreach ($robots as $i => $r) {
            $map[$r["p"][1]][$r["p"][0]]++;
        }
        foreach ($map as $y => $row) {
            $display = array_map(function($x) { return ($x > 0) ? '*' : ' '; }, $row);
            Logger::log(join('',$display));
        }
    }
}