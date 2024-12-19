<?php

namespace Advent;

use Advent\Logger;

class Day18
{
    public function run($lines) {
        
        $max = 70;
        $fallen = [];
        $count = 1024;
        foreach ($lines as $i => $line) {
            if ($i < $count && !isset($fallen[$line])) {
                $fallen[$line] = 1;
            }
        }

        $dirs = [[-1, 0], [0, 1], [1, 0], [0, -1]];
        $graph = [];
        for ($y=0; $y<($max+1); $y++) {
            for ($x=0; $x<($max+1); $x++) {
                if (!isset($fallen["$x,$y"])) {
                    $graph["$x,$y"] = [];
                    foreach ($dirs as $i => $m) {
                        $ny = $y+$m[0];
                        $nx = $x+$m[1];
                        if ($ny > -1 && $nx > -1 && $ny < ($max+1) && $nx < ($max+1) && !isset($fallen["$nx,$ny"])) {
                            $graph["$x,$y"]["$nx,$ny"] = 1;
                        }
                    }
                }
            }
        }
        // foreach($graph as $from => $to) {
        //     Logger::log("Can move from $from to:");
        //     Logger::log(json_encode($to));
        // }

        $distances = Day18::calc_distances($graph, "0,0");
        $p1 = $distances["$max,$max"];
        Logger::log("Part 1: $p1");

        // Logger::log("Part 2: $p2");
    }

    private static function calc_distances($graph, $start) {
        $max = 999999999;
        $distances = [];
        
        foreach ($graph as $node => $moves) {
            $distances[$node] = $max;
        }
        $distances[$start] = 0;

        $q = [[0, $start]];
        while (count($q) > 0) {
            usort($q, function($a, $b) { return $b[0] - $a[0]; });
            $curr = array_pop($q);

            if ($curr[0] > $distances[$curr[1]]) {
                continue;
            }
            foreach ($graph[$curr[1]] as $node => $weight) {
                $dist = $curr[0] + $weight;
                if ($dist < $distances[$node]) {
                    $distances[$node] = $dist;
                    array_push($q, [$dist, $node]);
                }
            }
        }
        return $distances;
    }
}