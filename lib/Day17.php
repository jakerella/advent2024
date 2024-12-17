<?php

namespace Advent;

use Advent\Logger;

class Day17
{
    public function run($lines) {
        $a = -1;
        $b = -1;
        $c = -1;
        $p = [];
        foreach ($lines as $i => $line) {
            preg_match_all('/\d+/', $line, $nums);
            $nums = array_map(function($n) { return (int) $n; }, $nums[0]);
            if ($a === -1) {
                $a = $nums[0];
            } else if ($b === -1) {
                $b = $nums[0];
            } else if ($c === -1) {
                $c = $nums[0];
            } else {
                $p = $nums;
            }
        }
        $p1 = join(',', Day17::run_program($a, $b, $c, $p));
        Logger::log("Part 1: $p1");


        // part 2
        $target = join(',', $p);
        $result = "";
        $a = pow(8, count($p)-1); // first time we get 16 output digits (35184372088832);
        // outputs change every 8^0 cycles in first place, 8^1 in second, 8^2 in third, ...
        while ($result != $target) {
            $out = Day17::run_program($a, $b, $c, $p);
            $result = join(',', $out);
            // check from the end, then incremement by powers of 8
            for ($pos=count($p)-1; $pos>-1; $pos--) {
                if ($p[$pos] != $out[$pos]) {
                    $a += pow(8, $pos);
                    break;
                }
            }
        }

        Logger::log("A=$a: $target :: $result");
        Logger::log("Part 2: $a");
    }

    private static function run_program($a, $b, $c, $p) {
        $i = 0;
        $out = [];
        while ($i < count($p)) {
            // Logger::log("(ptr=$i) op={$p[$i]} operand={$p[$i+1]} :: R: $a, $b, $c");
            if ($p[$i] == 0) {
                $a = floor($a / pow(2, Day17::get_combo($p[$i+1], $a, $b, $c)));
                $i+=2;
            } else if ($p[$i] == 1) {
                $b = $b ^ $p[$i+1];
                $i+=2;
            } else if ($p[$i] == 2) {
                $b = Day17::get_combo($p[$i+1], $a, $b, $c) % 8;
                $i+=2;
            } else if ($p[$i] == 3) {
                if ($a == 0) {
                    $i+=2;
                } else {
                    $i = $p[$i+1];
                }
            } else if ($p[$i] == 4) {
                $b = $b ^ $c;
                $i+=2;
            } else if ($p[$i] == 5) {
                array_push($out, (Day17::get_combo($p[$i+1], $a, $b, $c) % 8));
                $i+=2;
            } else if ($p[$i] == 6) {
                $b = floor($a / pow(2, Day17::get_combo($p[$i+1], $a, $b, $c)));
                $i+=2;
            } else if ($p[$i] == 7) {
                $c = floor($a / pow(2, Day17::get_combo($p[$i+1], $a, $b, $c)));
                $i+=2;
            } else {
                Logger::log("bad command: {$p[$i]}");
                $i+=2;
            }
        }
        return $out;
    }

    private static function get_combo($v, $a, $b, $c) {
        if ($v < 4) { return $v; }
        if ($v == 4) { return $a; }
        if ($v == 5) { return $b; }
        if ($v == 6) { return $c; }
        Logger::log("bad combo operand: $v");
    }
}