<?php

namespace Advent;

use Advent\Logger;

class Day12
{
    private $garden = [];
    private $handled = [];

    public function run($lines) {
        foreach ($lines as $i => $line) {
            array_push($this->garden, str_split($line));
        }
        
        $p1 = 0;
        $p2 = 0;
        foreach ($this->garden as $y => $row) {
            foreach ($row as $x => $plot) {
                if (array_search("$y,$x", $this->handled) === false) {
                    $type = $this->garden[$y][$x];
                    $region = $this->gather_plots($type, $y, $x, []);
                    $area = count($region);
                    $perimeter = array_reduce($region, function($prev, $curr) {
                        return $prev + $curr["edge_count"];
                    });
                    // Logger::log("Found region of type {$this->garden[$y][$x]} with area $area and perimeter $perimeter");
                    $p1 += ($area * $perimeter);
                    
                    $sides = 0;
                    foreach ($region as $loc => $plot) {
                        $py = $plot["coord"][0];
                        $px = $plot["coord"][1];
                        $up = $py-1;
                        $right = $px+1;
                        $down = $py+1;
                        $left = $px-1;
                        // concave
                        if ($plot["edges"][0] && $plot["edges"][3]) {
                            $sides++;
                        }
                        if ($plot["edges"][0] && $plot["edges"][1]) {
                            $sides++;
                        }
                        if ($plot["edges"][2] && $plot["edges"][1]) {
                            $sides++;
                        }
                        if ($plot["edges"][2] && $plot["edges"][3]) {
                            $sides++;
                        }
                        // convex
                        if (isset($region["$up,$px"]) && $region["$up,$px"]["edges"][1] &&
                            isset($region["$py,$right"]) && $region["$py,$right"]["edges"][0]) {
                            $sides++;
                        }
                        if (isset($region["$down,$px"]) && $region["$down,$px"]["edges"][1] &&
                            isset($region["$py,$right"]) && $region["$py,$right"]["edges"][2]) {
                            $sides++;
                        }
                        if (isset($region["$down,$px"]) && $region["$down,$px"]["edges"][3] &&
                            isset($region["$py,$left"]) && $region["$py,$left"]["edges"][2]) {
                            $sides++;
                        }
                        if (isset($region["$up,$px"]) && $region["$up,$px"]["edges"][3] &&
                            isset($region["$py,$left"]) && $region["$py,$left"]["edges"][0]) {
                            $sides++;
                        }
                    }
                    // Logger::log("Found region of type {$this->garden[$y][$x]} with area $area and sides $sides (total edges: $perimeter)");
                    $p2 += ($area * $sides);
                }
            }
        }

        Logger::log("Part 1: $p1");

        Logger::log("Part 2: $p2");
    }

    private function gather_plots($type, $y, $x, $region) {
        array_push($this->handled, "$y,$x");

        $plot = array("coord" => [$y, $x], "edge_count" => 4, "edges" => [1, 1, 1, 1]);

        if (isset($this->garden[$y-1][$x]) && $this->garden[$y-1][$x] === $type) {
            $plot["edge_count"]--;
            $plot["edges"][0] = 0;
            $next = $y-1;
            if (array_search("$next,$x", $this->handled) === false) {
                $region = $this->gather_plots($type, $y-1, $x, $region);
            }
        }
        if (isset($this->garden[$y][$x+1]) && $this->garden[$y][$x+1] === $type) {
            $plot["edge_count"]--;
            $plot["edges"][1] = 0;
            $next = $x+1;
            if (array_search("$y,$next", $this->handled) === false) {
                $region = $this->gather_plots($type, $y, $x+1, $region);
            }
        }
        if (isset($this->garden[$y+1][$x]) && $this->garden[$y+1][$x] === $type) {
            $plot["edge_count"]--;
            $plot["edges"][2] = 0;
            $next = $y+1;
            if (array_search("$next,$x", $this->handled) === false) {
                $region = $this->gather_plots($type, $y+1, $x, $region);
            }
        }
        if (isset($this->garden[$y][$x-1]) && $this->garden[$y][$x-1] === $type) {
            $plot["edge_count"]--;
            $plot["edges"][3] = 0;
            $next = $x-1;
            if (array_search("$y,$next", $this->handled) === false) {
                $region = $this->gather_plots($type, $y, $x-1, $region);
            }
        }

        $region["$y,$x"] = $plot;

        return $region;
    }
}