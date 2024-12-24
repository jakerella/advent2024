<?php

namespace Advent;

use Advent\Logger;

class Day24
{
    public function run($lines) {
        $known = [];
        $unsolved = [];
        foreach ($lines as $i => $line) {
            if (preg_match('/\:/', $line)) {
                $wire = preg_split('/\:\s/', $line);
                $known[$wire[0]] = $wire[1];
            } else if (preg_match('/\-\>/', $line)) {
                preg_match('/([a-z0-9]+)\s(AND|OR|XOR)\s([a-z0-9]+)\s\-\>\s([a-z0-9]+)/', $line, $gate);
                array_push($unsolved, array_slice($gate, 1));
            }
        }
        // Logger::log(json_encode($unsolved));
        $i = 0;
        while (count($unsolved)) {
            if ($i >= count($unsolved)) { $i = 0; }
            if (isset($known[$unsolved[$i][0]]) && isset($known[$unsolved[$i][2]])) {
                $out = 0;
                if ($unsolved[$i][1] == "AND" && $known[$unsolved[$i][0]] == "1" && $known[$unsolved[$i][2]] == "1") {
                    $out = 1;
                } else if ($unsolved[$i][1] == "OR" && ($known[$unsolved[$i][0]] == "1" || $known[$unsolved[$i][2]] == "1")) {
                    $out = 1;
                } else if ($unsolved[$i][1] == "XOR" && $known[$unsolved[$i][0]] != $known[$unsolved[$i][2]]) {
                    $out = 1;
                }
                // Logger::log("Gate {$known[$unsolved[$i][0]]} {$unsolved[$i][1]} {$known[$unsolved[$i][2]]} => $out");
                $known[$unsolved[$i][3]] = $out;
                array_splice($unsolved, $i, 1);
            } else {
                $i++;
            }
        }
        // Logger::log(json_encode($known));

        $z = array_filter($known, function($wire) { return preg_match('/^z/', $wire); }, ARRAY_FILTER_USE_KEY);
        krsort($z);
        $b = join('', $z);
        $p1 = bindec($b);
        Logger::log("Part 1: $p1 ($b)");

        // Logger::log("Part 2: $p2");
    }
}