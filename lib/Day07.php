<?php

namespace Advent;

use Advent\Logger;

class Day07
{   
    public function run($lines) {
        $p1_sum = 0;
        $p2_sum = 0;
        foreach ($lines as $line_num => $line) {
            $equation = preg_split('/\:\s/', $line);
            if (count($equation) !== 2) { continue; }

            $target = (int) $equation[0];
            $nums = array_map(function ($n) { return (int) $n; }, preg_split('/\s/', $equation[1]));
            if (Day07::addOrMultiply($target, $nums)) {
                $p1_sum += $target;
            }
            if (Day07::addMultiplyOrConcat($target, $nums)) {
                $p2_sum += $target;
            }
        }

        Logger::log("Part 1: $p1_sum");
        Logger::log("Part 2: $p2_sum");
    }

    private static function addOrMultiply($target, $nums) {
        if (count($nums) == 1) {
            return ($nums[0] == $target);
        }
        if (Day07::addOrMultiply($target, array_merge([$nums[0] * $nums[1]], array_slice($nums, 2)))) {
            return true;
        }
        return Day07::addOrMultiply($target, array_merge([$nums[0] + $nums[1]], array_slice($nums, 2)));
    }

    private static function addMultiplyOrConcat($target, $nums) {
        if (count($nums) == 1) {
            return ($nums[0] == $target);
        }
        if (Day07::addMultiplyOrConcat($target, array_merge([$nums[0] * $nums[1]], array_slice($nums, 2)))) {
            return true;
        }
        if (Day07::addMultiplyOrConcat($target, array_merge([$nums[0] + $nums[1]], array_slice($nums, 2)))) {
            return true;
        }

        // THIS DOES NOT WORK IN PHP! WORKS IN JS JUST FINE...
        $concat = (int) "{$nums[0]}{$nums[1]}";
        return Day07::addMultiplyOrConcat($target, array_merge([$concat], array_slice($nums, 2)));
    }
}