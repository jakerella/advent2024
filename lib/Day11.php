<?php

namespace Advent;

use Advent\Logger;

class Day11
{   
    private $known = [];

    public function run($lines) {
        $stones = preg_split('/\s/', $lines[0]);
        $stones = array_map(function($n) { return (int) $n; }, $stones);

        // part 1 (brute force)
        // for ($i=0; $i<75; $i++) {
        //     $new_stones = [];
        //     foreach ($stones as $si => $num) {
        //         if ($num == 0) {
        //             array_push($new_stones, 1);
        //         } else if (strlen($num) % 2 === 0) {
        //             $chars = str_split($num);
        //             $left = (string) ((int) join('', array_slice($chars, 0, count($chars) / 2)));
        //             $right = (string) ((int) join('', array_slice($chars, count($chars) / 2)));
        //             array_push($new_stones, $left, $right);
        //         } else {
        //             array_push($new_stones, (string) (((int) $num) * 2024));
        //         }
        //     }
        //     $stones = $new_stones;
        //     // var_dump(json_encode($stones));
        // }
        // $p1 = count($stones);

        $p1 = 0;
        $p2 = 0;
        foreach ($stones as $i => $num) {
            $p1 += $this->count_stones($num, 25);
            $p2 += $this->count_stones($num, 75);
        }
        Logger::log("Part 1: $p1");
        Logger::log("Part 2: $p2");
    }

    private function count_stones($num, $steps) {
        $num = (int) $num;
        if (isset($this->known["$num-$steps"])) {
            return $this->known["$num-$steps"];
        }

        if ($steps === 1) {
            return (strlen("$num") % 2 === 0) ? 2 : 1;
        }

        $count = 0;
        if ($num === 0) {
            $count = $this->count_stones(1, $steps-1);

        } else if (strlen("$num") % 2 === 0) {
            $chars = str_split($num);
            $left = $this->count_stones((string) ((int) join('', array_slice($chars, 0, count($chars) / 2))), $steps-1);
            $right = $this->count_stones((string) ((int) join('', array_slice($chars, count($chars) / 2))), $steps-1);
            $count = $left + $right;

        } else {
            $count = $this->count_stones($num * 2024, $steps-1);
        }
        $this->known["$num-$steps"] = $count;
        return $count;
    }
}