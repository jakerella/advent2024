<?php

namespace Advent;

use Advent\Logger;

class Day13
{   
    public function run($lines) {
        $machines = [];
        $i = 0;
        while ($i<count($lines)) {
            preg_match_all('/\d+/', $lines[$i].$lines[$i+1].$lines[$i+2], $nums);
            $nums = array_map(function($n) { return (int) $n; }, $nums[0]);
            array_push($machines, array(
                "a"=>[$nums[0], $nums[1]],
                "b"=>[$nums[2], $nums[3]],
                "p"=>[$nums[4], $nums[5]]
            ));
            $i += 4;
        }
        // Logger::log(json_encode($machines));

        $p1 = 0;
        $p2 = 0;
        foreach ($machines as $i => $m) {
            $p1 += Day13::calc_button_presses($m, 0);
            $p2 += Day13::calc_button_presses($m, 10000000000000);
        }
        Logger::log("Part 2: $p1");
        Logger::log("Part 2: $p2");
    }

    private static function calc_button_presses($m, $add) {
        $target_x = $m["p"][0] + $add;
        $target_y = $m["p"][1] + $add;
        // $target_x = ($a * $m["a"][0]) + ($b * $m["b"][0])  and
        // $target_y = ($a * $m["a"][1]) + ($b * $m["b"][1])
        // introduce $m["a"][1] (Y movement of buton A) to both sides and solve for $b (presses needed)
        $b = (($target_x * $m["a"][1]) - ($target_y * $m["a"][0])) / (($m["b"][0] * $m["a"][1]) - ($m["b"][1] * $m["a"][0]));
        // introduce $m["a"][0] (X movement of buton A) to both sides and solve for $a (presses needed)
        $a = (($target_x * $m["b"][1]) - ($target_y * $m["b"][0])) / (($m["b"][1] * $m["a"][0]) - ($m["b"][0] * $m["a"][1]));
        // check that a and b are even numbers (button presses)
        if (floor($b) == $b && floor($a) == $a) {
            $c = ($a * 3) + ($b * 1);
            // Logger::log("machine $i: A=$a, B=$b and cost=$c");
            return $c;
        }
    }
}