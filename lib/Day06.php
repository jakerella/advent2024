<?php

namespace Advent;

use Advent\Logger;

class Day06
{   
    public function run($input) {
        Logger::log("Day 06 Start");
        $lines = file($input);

        $dir = 'N';
        $pos = [];
        $map = [];
        $visited = [];
        $visited_with_dir = [];
        foreach ($lines as $y => $line) {
            $map[$y] = str_split($line);
            foreach ($map[$y] as $x => $spot) {
                if ($spot == '^') {
                    $pos[0] = $y;
                    $pos[1] = $x;
                    Logger::log("starting at [$y, $x]");
                    array_push($visited, "$y,$x");
                    $visited_with_dir["{$y},{$x}"] = ['N'];
                }
            }
        }

        $loop_count = 0;
        $turn_dir = array('N'=>'E', 'E'=>'S', 'S'=>'W', 'W'=>'N');
        while (true) {
            $next = Day06::next_spot($map, $dir, $pos);
            if ($next == null) {
                break;
            } else {
                // see if we can block the way and create a loop (part 2)
                if (Day06::check_for_overlap($map, $visited_with_dir, $pos, $turn_dir[$next[2]], [$pos[0], $pos[1]])) {
                    // Logger::log("found overlap if blocking [{$next[0]}, {$next[1]}]");
                    $loop_count++;
                }
                
                $pos = [$next[0], $next[1]];
                $dir = $next[2];
                if (array_search($pos[0].",".$pos[1], $visited) === false) {
                    array_push($visited, $pos[0].",".$pos[1]);
                }
                if (!isset($visited_with_dir["{$pos[0]},{$pos[1]}"])) {
                    $visited_with_dir["{$pos[0]},{$pos[1]}"] = [];
                }
                if (array_search($dir, $visited_with_dir["{$pos[0]},{$pos[1]}"]) === false) {
                    array_push($visited_with_dir["{$pos[0]},{$pos[1]}"], $dir);
                }
            }
        }
        $count = count($visited);
        // $path = json_encode($visited);
        // $dirs = json_encode($visited_with_dir);
        // Logger::log("PATH:\n$path");
        // Logger::log("DIRS:\n$dirs");
        Logger::log("Part 1: $count");

        // 1433 too low
        Logger::log("Part 2: $loop_count");
    }

    private static function check_for_overlap($map, $visited, $pos, $dir, $start) {
        $turn_dir = array('N'=>'E', 'E'=>'S', 'S'=>'W', 'W'=>'N');
        $y = $pos[0];
        $x = $pos[1];
        while (true) {
            if ($dir == 'N') { $y--; }
            if ($dir == 'E') { $x++; }
            if ($dir == 'S') { $y++; }
            if ($dir == 'W') { $x--; }
            if ($y < 0 || $y > count($map)-1 || $x < 0 || $x > count($map[0])-1) {
                return false;  // off the map
            }
            if ($y == $start[0] && $x == $start[1]) {
                return true;  // back where we started, so created a new loop
            }
            if ($map[$y][$x] == '#') {
                // turn and keep checking
                return Day06::check_for_overlap($map, $visited, [$y,$x], $turn_dir[$dir], $start);
            }
            if (isset($visited["{$y},{$x}"]) && array_search($dir, $visited["{$y},{$x}"]) !== false) {
                // we're on a path we've been on before, so we are in a loop
                return true;
            }
        }
    }

    private static function next_spot($map, $dir, $pos) {
        $next = [];
        if ($dir == 'N') {
            if ($pos[0] < 1) { return null; }  // off map north
            if ($map[$pos[0]-1][$pos[1]] == '#') {
                if ($pos[1] >= count($map[0])-1) { return null; }  // off map east
                if ($map[$pos[0]][$pos[1]+1] == '#') {
                    // boxed in, go back
                    $next [0] = $pos[0]+1;
                    $next [1] = $pos[1];
                    $next[2] = 'S';
                } else {
                    // turn east
                    $next [0] = $pos[0];
                    $next [1] = $pos[1]+1;
                    $next[2] = 'E';
                }
            } else {
                // go north
                $next [0] = $pos[0]-1;
                $next [1] = $pos[1];
                $next[2] = 'N';
            }
        } else if ($dir == 'E') {
            if ($pos[1] >= count($map[0])-1) { return null; }  // off map east
            if ($map[$pos[0]][$pos[1]+1] == '#') {
                if ($pos[0] >= count($map)-1) { return null; }  // off map south
                if ($map[$pos[0]+1][$pos[1]] == '#') {
                    // boxed in, go back
                    $next [0] = $pos[0];
                    $next [1] = $pos[1]-1;
                    $next[2] = 'W';
                } else {
                    // turn south
                    $next [0] = $pos[0]+1;
                    $next [1] = $pos[1];
                    $next[2] = 'S';
                }
            } else {
                // go east
                $next [0] = $pos[0];
                $next [1] = $pos[1]+1;
                $next[2] = 'E';
            }
        } else if ($dir == 'S') {
            if ($pos[0] >= count($map)-1) { return null; }  // off map south
            if ($map[$pos[0]+1][$pos[1]] == '#') {
                if ($pos[1] < 1) { return null; }  // off map west
                if ($map[$pos[0]][$pos[1]-1] == '#') {
                    // boxed in, go back
                    $next [0] = $pos[0]-1;
                    $next [1] = $pos[1];
                    $next[2] = 'N';
                } else {
                    // turn west
                    $next [0] = $pos[0];
                    $next [1] = $pos[1]-1;
                    $next[2] = 'W';
                }
            } else {
                // go south
                $next [0] = $pos[0]+1;
                $next [1] = $pos[1];
                $next[2] = 'S';
            }
        } else if ($dir == 'W') {
            if ($pos[1] < 1) { return null; }  // off map west
            if ($map[$pos[0]][$pos[1]-1] == '#') {
                if ($pos[0] < 1) { return null; }  // off map north
                if ($map[$pos[0]-1][$pos[1]] == '#') {
                    // boxed in, go back
                    $next [0] = $pos[0];
                    $next [1] = $pos[1]+1;
                    $next[2] = 'E';
                } else {
                    // turn north
                    $next [0] = $pos[0]-1;
                    $next [1] = $pos[1];
                    $next[2] = 'N';
                }
            } else {
                // go west
                $next [0] = $pos[0];
                $next [1] = $pos[1]-1;
                $next[2] = 'W';
            }
        } else {
            throw new Error("Invalid direction: $dir");
        }
        // if ($next[2] != $dir) {
        //     Logger::log("turning {$next[2]}, now at [{$next[0]}, {$next[1]}]");
        // }
        return $next;
    }
}