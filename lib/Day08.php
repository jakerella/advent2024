<?php

namespace Advent;

use Advent\Logger;

class Day08
{   
    public function run($lines) {
        $max_y = count($lines)-1;
        $max_x = 0;
        $antenna_types = [];
        foreach ($lines as $y => $line) {
            $nodes = str_split($line);
            $max_x = count($nodes)-1;
            foreach ($nodes as $x => $node) {
                if ($node != '.') {
                    if (!isset($antenna_types[$node])) {
                        $antenna_types[$node] = [];
                    }
                    array_push($antenna_types[$node], [$y, $x]);
                }
            }
        }

        $antinodes = [];
        $resonant_antinodes = [];
        foreach ($antenna_types as $type => $locations) {
            foreach ($locations as $loc_i => $loc) {
                for ($pair_i = $loc_i+1; $pair_i < count($locations); $pair_i++) {
                    $y1 = $loc[0];
                    $x1 = $loc[1];
                    $y2 = $locations[$pair_i][0];
                    $x2 = $locations[$pair_i][1];
                    $diff_y = $y2 - $y1;
                    $diff_x = $x2 - $x1;

                    // part 1 only
                    $antinode_y1 = $y1 - $diff_y;
                    $antinode_x1 = $x1 - $diff_x;
                    $antinode_y2 = $y2 + $diff_y;
                    $antinode_x2 = $x2 + $diff_x;
                    // Logger::log("'$type' antinodes for pair [$y1, $x1] => [$y2, $x2]: [$antinode_y1, $antinode_x1] and [$antinode_y2, $antinode_x2]");
                    if ($antinode_y1 > -1 && $antinode_x1 > -1 && $antinode_y1 <= $max_y && $antinode_x1 <= $max_x) {
                        if (array_search("$antinode_y1,$antinode_x1", $antinodes) === false) {
                            array_push($antinodes, "$antinode_y1,$antinode_x1");
                        }
                    }
                    if ($antinode_y2 > -1 && $antinode_x2 > -1 && $antinode_y2 <= $max_y && $antinode_x2 <= $max_x) {
                        if (array_search("$antinode_y2,$antinode_x2", $antinodes) === false) {
                            array_push($antinodes, "$antinode_y2,$antinode_x2");
                        }
                    }

                    if (array_search("$y1,$x1", $resonant_antinodes) === false) {
                        array_push($resonant_antinodes, "$y1,$x1");
                    }
                    if (array_search("$y2,$x2", $resonant_antinodes) === false) {
                        array_push($resonant_antinodes, "$y2,$x2");
                    }

                    $multiplier = 1;
                    $antinode_y1 = $y1 - $diff_y;
                    $antinode_x1 = $x1 - $diff_x;
                    while ($antinode_y1 > -1 && $antinode_x1 > -1) {
                        if ($antinode_y1 > -1 && $antinode_x1 > -1 && $antinode_y1 <= $max_y && $antinode_x1 <= $max_x) {
                            if (array_search("$antinode_y1,$antinode_x1", $resonant_antinodes) === false) {
                                array_push($resonant_antinodes, "$antinode_y1,$antinode_x1");
                            }
                        }
                        $multiplier++;
                        $antinode_y1 -= $diff_y;
                        $antinode_x1 -= $diff_x;
                    }

                    $multiplier = 1;
                    $antinode_y2 = $y2 + $diff_y;
                    $antinode_x2 = $x2 + $diff_x;
                    while ($antinode_y2 <= $max_y && $antinode_x2 <= $max_x) {
                        if ($antinode_y2 > -1 && $antinode_x2 > -1 && $antinode_y2 <= $max_y && $antinode_x2 <= $max_x) {
                            if (array_search("$antinode_y2,$antinode_x2", $resonant_antinodes) === false) {
                                array_push($resonant_antinodes, "$antinode_y2,$antinode_x2");
                            }
                        }
                        $multiplier++;
                        $antinode_y2 += $diff_y;
                        $antinode_x2 += $diff_x;
                    }
                }
            }
        }

        $map = "";
        for ($y = 0; $y <= $max_y; $y++) {
            $map .= "\n";
            for ($x = 0; $x <= $max_x; $x++) {
                if (array_search("$y,$x", $antinodes) !== false) {
                    $map .= "#";
                } else if (array_search("$y,$x", $resonant_antinodes) !== false) {
                    $map .= "*";
                } else {
                    $map .= ".";
                }
            }
        }
        // echo "$map\n\n";

        $p1 = count($antinodes);
        Logger::log("Part 1: $p1");

        $p2 = count($resonant_antinodes);
        // 1018 too low
        // 1133 too high
        Logger::log("Part 2: $p2");
    }

}