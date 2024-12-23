<?php

namespace Advent;

use Advent\Logger;

class Day16
{
    private $cache = [];

    public function run($lines) {
        $map = [];
        foreach ($lines as $y => $line) {
            array_push($map, str_split($line));
        }

        $graph = []; // [from][to], where the "to" could be another spot, or just a pivot
        $start = "";
        $ends = [];
        $dirs = [[-1, 0], [0, 1], [1, 0], [0, -1]];
        foreach ($map as $y => $row) {
            foreach ($row as $x => $spot) {
                if ($spot == "S") {
                    $start = "$y-$x-1";
                }
                if ($spot == "E") {
                    $ends = ["$y-$x-0", "$y-$x-1", "$y-$x-2", "$y-$x-3"];
                }
                if ($spot != "#") {
                    foreach ($dirs as $i => $m) {
                        $graph["$y-$x-$i"] = [];
                        // moves
                        $my = $y + $m[0];
                        $mx = $x + $m[1];
                        if ($map[$my][$mx] != "#") {
                            $graph["$y-$x-$i"]["$my-$mx-$i"] = 1;
                        }

                        // pivots
                        $left = (($i-1) < 0) ? 3 : $i-1;
                        $right = (($i+1) > 3) ? 0 : $i+1;
                        $graph["$y-$x-$i"]["$y-$x-$left"] = 1000;
                        $graph["$y-$x-$i"]["$y-$x-$right"] = 1000;
                    }
                }
            }
        }
        // Logger::log("Start=[{$start[0]}, {$start[1]}] and End=[{$end[0]}, {$end[1]}]");
        // Day16::show_map($map);
        // Logger::log(json_encode($graph["13,1-0"]));

        $results = Day16::calc_distances($graph, $start);
        $distances = $results[0];
        $paths = $results[1];

        $p1 = 9999999999;
        foreach ($ends as $i => $end) {
            // Logger::log("dist to $end: {$distances[$end]}");
            if ($distances[$end] < $p1) { $p1 = $distances[$end]; }
        }
        Logger::log("Part 1: $p1");

        $seats = [];
        foreach ($ends as $i => $end) {
            if ($distances[$end] == $p1) {
                // Logger::log("finding path to $end ({$distances[$end]})");

                $q = [$end];
                while (count($q) > 0) {
                    $next = array_pop($q);
                    $spot = preg_split('/\-/', $next);
                    if (!isset($seats["{$spot[0]}-{$spot[1]}"])) {
                        $seats["{$spot[0]}-{$spot[1]}"] = 1;
                    }
                    // Logger::log("access $next in paths:");
                    // Logger::log(json_encode($paths[$next]));
                    foreach ($paths[$next] as $i => $node) {
                        if ($node != $start) {
                            array_push($q, $node);
                        }
                    }
                }
                $s = preg_split('/\-/', $start);
                $seats["{$s[0]}-{$s[1]}"] = 1;
            }
        }
        // Logger::log(json_encode($seats));

        $p2 = count($seats);
        Logger::log("Part 2: $p2");
    }

    private static function calc_distances($graph, $start) {
        $max = 999999999;
        $distances = [];
        $paths = [];
        
        foreach ($graph as $node => $moves) {
            $distances[$node] = $max;
            $paths[$node] = [];
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
                    $paths[$node] = [$curr[1]];
                    array_push($q, [$dist, $node]);
                } else if ($dist == $distances[$node]) {
                    array_push($paths[$node], $curr[1]);
                }
            }
        }
        return [$distances, $paths];
    }

    private static function show_map($map) {
        foreach ($map as $y => $row) {
            Logger::log(join('', array_map(function ($s) { return $s[0]; }, $row)));
        }
    }
}