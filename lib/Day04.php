<?php

namespace Advent;

use Advent\Logger;

class Day04
{
    public function run($input) {
        Logger::log("Day 04 Start");
        $lines = file($input);

        $puzzle = [];
        foreach ($lines as $line_num => $line) {
            $puzzle[$line_num] = str_split($line);
            
            if ($line_num == 0 || ($line_num > 0 && count($puzzle[$line_num]) > count($puzzle[0]))) {
                array_pop($puzzle[$line_num]);
            }
        }
        // var_dump($puzzle);
        $count_words = 0;
        $count_x = 0;
        foreach ($puzzle as $y => $row) {
            foreach ($row as $x => $letter) {
                if ($letter == 'X') {
                    $count_words += Day04::countWords($puzzle, $x, $y);
                }
                if ($letter == 'A' && Day04::isCross($puzzle, $x, $y)) {
                    $count_x++;
                }
            }
        }

        Logger::log("Part 1: $count_words");
        Logger::log("Part 2: $count_x");
    }

    private static function countWords($puzzle, $x, $y) {
        $right = $puzzle[$y][$x] . $puzzle[$y][$x+1] . $puzzle[$y][$x+2] . $puzzle[$y][$x+3];
        $left = $puzzle[$y][$x] . $puzzle[$y][$x-1] . $puzzle[$y][$x-2] . $puzzle[$y][$x-3];
        $up = $puzzle[$y][$x] . $puzzle[$y-1][$x] . $puzzle[$y-2][$x] . $puzzle[$y-3][$x];
        $down = $puzzle[$y][$x] . $puzzle[$y+1][$x] . $puzzle[$y+2][$x] . $puzzle[$y+3][$x];
        $upleft = $puzzle[$y][$x] . $puzzle[$y-1][$x-1] . $puzzle[$y-2][$x-2] . $puzzle[$y-3][$x-3];
        $upright = $puzzle[$y][$x] . $puzzle[$y-1][$x+1] . $puzzle[$y-2][$x+2] . $puzzle[$y-3][$x+3];
        $downleft = $puzzle[$y][$x] . $puzzle[$y+1][$x-1] . $puzzle[$y+2][$x-2] . $puzzle[$y+3][$x-3];
        $downright = $puzzle[$y][$x] . $puzzle[$y+1][$x+1] . $puzzle[$y+2][$x+2] . $puzzle[$y+3][$x+3];
        
        $count = 0;
        if ($right == 'XMAS') { $count++; }
        if ($left == 'XMAS') { $count++; }
        if ($up == 'XMAS') { $count++; }
        if ($down == 'XMAS') { $count++; }
        if ($upleft == 'XMAS') { $count++; }
        if ($upright == 'XMAS') { $count++; }
        if ($downleft == 'XMAS') { $count++; }
        if ($downright == 'XMAS') { $count++; }

        return $count;
    }

    private static function isCross($puzzle, $x, $y) {
        $upleft = $puzzle[$y-1][$x-1];
        $upright = $puzzle[$y-1][$x+1];
        $downleft = $puzzle[$y+1][$x-1];
        $downright = $puzzle[$y+1][$x+1];
        if ((($upleft == 'M' && $downright == 'S') ||
            ($upleft == 'S' && $downright == 'M')) &&
            (($upright == 'M' && $downleft == 'S') ||
            ($upright == 'S' && $downleft == 'M'))) {
            return true;
        }
        return false;
    }
}