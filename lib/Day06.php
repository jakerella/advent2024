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
        foreach ($lines as $y => $line) {
            $map[$y] = str_split($line);
            array_pop($map[$y]);
            foreach ($map[$y] as $x => $spot) {
                if ($spot == '^') {
                    $pos[0] = $y;
                    $pos[1] = $x;
                    Logger::log("starting at [$y, $x]");
                    array_push($visited, "$y,$x");
                }
            }
        }
        $start_pos = $pos;

        $loop_count = 0;
        while (true) {
            $next = Day06::next_spot($map, $dir, $pos);
            if ($next == null) {
                break;
            } else {
                $pos = [$next[0], $next[1]];
                $dir = $next[2];
                if (array_search($pos[0].",".$pos[1], $visited) === false) {
                    array_push($visited, $pos[0].",".$pos[1]);
                }
            }
        }
        $count = count($visited);
        Logger::log("Part 1: $count");


        $loop_count = 0;
        foreach ($map as $y => $row) {
            foreach ($map[$y] as $x => $col) {
                if ($map[$y][$x] == '#') { continue; }
                $newMap = $map;
                $newMap[$y][$x] = '#';
                if (Day06::check_for_loop($newMap, $start_pos)) {
                    $loop_count++;
                }
            }
        }

        Logger::log("Part 2: $loop_count");
    }

    private static function check_for_loop($map, $pos) {
        $curr = $pos;
        $visited = array("{$curr[0]},{$curr[1]}"=>['N']);
        $dir = 'N';
        while (true) {
            $next = Day06::next_spot($map, $dir, $curr);
            if ($next == null) {
                return false;  // off the map
            } else if (isset($visited["{$next[0]},{$next[1]}"]) && array_search($next[2], $visited["{$next[0]},{$next[1]}"]) !== false) {
                // we're on a path we've been on before, so we are in a loop
                return true;
            }
            $curr = [$next[0], $next[1]];
            $dir = $next[2];
            if (!isset($visited["{$next[0]},{$next[1]}"])) {
                $visited["{$next[0]},{$next[1]}"] = [];
            }
            if (array_search($dir, $visited["{$next[0]},{$next[1]}"]) === false) {
                array_push($visited["{$next[0]},{$next[1]}"], $dir);
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
            Logger::error("Invalid direction: $dir");
            throw new Exception();
        }
        return $next;
    }
}