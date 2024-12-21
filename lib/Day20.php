<?php

namespace Advent;

use Advent\Logger;

class Day20
{
    public function run($lines) {
        $min_save = 100;

        $start = [];
        $end = [];
        $map = [];
        foreach ($lines as $y => $line) {
            $row = str_split($line);
            $s = array_search("S", $row);
            if ($s !== false) {
                $start = [$y, $s];
            }
            $e = array_search("E", $row);
            if ($e !== false) {
                $end = [$y, $e];
            }
            array_push($map, $row);
        }
        // Logger::log("Start [{$start[0]}, {$start[1]}], End [{$end[0]}, {$end[1]}]");
        // Day20::show_map($map);

        $dirs = [[-1, 0], [0, 1], [1, 0], [0, -1]];
        $path["{$start[0]},{$start[1]}"] = 0;
        $spot = [$start[0], $start[1]];
        $i = 1;
        while ($spot[0] != $end[0] || $spot[1] != $end[1]) {
            foreach ($dirs as $d => $m) {
                $my = $spot[0] + $m[0];
                $mx = $spot[1] + $m[1];
                if (!isset($path["$my,$mx"]) && ($map[$my][$mx] == "." || $map[$my][$mx] == "E")) {
                    $spot = [$my, $mx];
                    $path["$my,$mx"] = $i;
                    break;
                }
            }
            $i++;
        }
        // Logger::log(json_encode($path));

        $p1_cheats = [];
        foreach ($path as $spot => $pos) {
            $c = array_map(function($n) { return (int) $n; }, preg_split('/,/', $spot));
            $p1_cheats = array_merge($p1_cheats, Day20::cheat_paths($map, $path, $c, $pos));
        }
        $p1 = 0;
        foreach ($p1_cheats as $nodes => $save) {
            if ($save >= $min_save) {
                $p1++;
            }
        }
        Logger::log("Part 1: $p1");


        // part 2
        $p2_cheats = [];
        foreach ($path as $spot => $pos) {
            $c = array_map(function($n) { return (int) $n; }, preg_split('/,/', $spot));
            $p2_cheats = array_merge($p2_cheats, Day20::cheat_paths_p2($map, $path, $c, $pos));
        }
        $p2 = 0;
        foreach ($p2_cheats as $nodes => $save) {
            if ($save >= $min_save) {
                $p2++;
            }
        }
        // Logger::log(json_encode($p2_cheats));
        Logger::log("Part 2: $p2");
    }

    private static function cheat_paths_p2($map, $path, $c, $pos) {
        $cheats = [];
        for ($my=-20; $my<21; $my++) {
            for ($mx=-20; $mx<21; $mx++) {
                $dist = abs($my) + abs($mx);
                if ($dist <= 20) {
                    $ny = $c[0] + $my;
                    $nx = $c[1] + $mx;
                    if (($map[$ny][$nx] == "." || $map[$ny][$nx] == "E") && $path["$ny,$nx"] > ($pos+1)) {
                        $save = $path["$ny,$nx"] - $pos - $dist;
                        $cheats["{$c[0]},{$c[1]}-$ny,$nx"] = $save;
                    }
                }
            }
        }
        return $cheats;
    }

    private static function cheat_paths($map, $path, $c, $pos) {
        $cheats = [];
        $dirs = [[-1, 0], [0, 1], [1, 0], [0, -1]];
        foreach ($dirs as $d => $m) {
            $my = $c[0] + $m[0];
            $mx = $c[1] + $m[1];
            if ($map[$my][$mx] == "#") {
                $m2y = $my + $m[0];
                $m2x = $mx + $m[1];
                if (($map[$m2y][$m2x] == "." || $map[$m2y][$m2x] == "E") && $path["$m2y,$m2x"] > $pos) {
                    $save = $path["$m2y,$m2x"] - $pos - 2; // -2 because we would have moved to that target spot anyway and zero based
                    // Logger::log("Cheat from [{$c[0]}, {$c[1]}] ($pos) to [$m2y,$m2x] ({$path["$m2y,$m2x"]}) => $save");
                    $cheats["{$c[0]},{$c[1]}-$my,$mx"] = $save;
                }
            }
        }
        return $cheats;
    }

    private static function show_map($map) {
        foreach ($map as $y => $row) {
            Logger::log(join('', array_map(function ($s) { return $s[0]; }, $row)));
        }
    }
}