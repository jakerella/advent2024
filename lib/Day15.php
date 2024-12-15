<?php

namespace Advent;

use Advent\Logger;

class Day15
{   
    public function run($lines) {
        
        // part 1
        $map = [];
        $moves = [];
        $pos = [0, 0];
        foreach ($lines as $y => $line) {
            if (preg_match('/^\#/', $line) === 1) {
                $row = str_split($line);
                array_push($map, $row);
                $x = array_search("@", $row);
                if ($x !== false) {
                    $pos = [$x, $y];
                }
            } else if (strlen($line) > 0) {
                $moves = array_merge($moves, str_split($line));
            }
        }
        // Day15::show_map($map, 0, "");
        
        foreach ($moves as $i => $dir) {
            if ($dir == "<") {
                $next = [$pos[0] - 1, $pos[1]];
            } else if ($dir == ">") {
                $next = [$pos[0] + 1, $pos[1]];
            } else if ($dir == "^") {
                $next = [$pos[0], $pos[1] - 1];
            } else if ($dir == "v") {
                $next = [$pos[0], $pos[1] + 1];
            }
            $move_box = false;
            $can_move = false;
            $n = json_encode($next);
            while ($map[$next[1]][$next[0]] != "#") {
                if ($map[$next[1]][$next[0]] == "O") {
                    $move_box = true;
                } else if ($map[$next[1]][$next[0]] == ".") {
                    if ($move_box) {
                        $map[$next[1]][$next[0]] = "O";
                        $can_move = true;
                        break;
                    } else {
                        $can_move = true;
                        break;
                    }
                }
                if ($dir == "<") {
                    $next[0]--;
                } else if ($dir == ">") {
                    $next[0]++;
                } else if ($dir == "^") {
                    $next[1]--;
                } else if ($dir == "v") {
                    $next[1]++;
                }
            }
            if ($can_move) {
                $map[$pos[1]][$pos[0]] = ".";
                if ($dir == "<") {
                    $pos[0]--;
                } else if ($dir == ">") {
                    $pos[0]++;
                } else if ($dir == "^") {
                    $pos[1]--;
                } else if ($dir == "v") {
                    $pos[1]++;
                }
                $map[$pos[1]][$pos[0]] = "@";
            }
            // Day15::show_map($map, $i+1, $dir);
        }

        $p1 = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $spot) {
                if ($spot == "O") {
                    $p1 += (100 * $y) + $x;
                }
            }
        }
        Logger::log("Part 1: $p1");


        // part 2
        $map = [];
        $moves = [];
        $pos = [0, 0];
        foreach ($lines as $y => $line) {
            if (preg_match('/^\#/', $line) === 1) {
                $map[$y] = [];
                $row = str_split($line);
                foreach ($row as $x => $spot) {
                    if ($spot == "@") {
                        $add = ".";
                        $pos = [count($map[$y]), $y];
                    } else if ($spot == "O") {
                        $spot = "[";
                        $add = "]";
                    } else {
                        $add = $spot;
                    }
                    array_push($map[$y], $spot, $add);
                }
            } else if (strlen($line) > 0) {
                $moves = array_merge($moves, str_split($line));
            }
        }
        // Day15::show_map($map, 0, "");

        foreach ($moves as $i => $dir) {
            if ($dir == "<") {
                $next = [$pos[0] - 1, $pos[1]];
            } else if ($dir == ">") {
                $next = [$pos[0] + 1, $pos[1]];
            } else if ($dir == "^") {
                $next = [$pos[0], $pos[1] - 1];
            } else if ($dir == "v") {
                $next = [$pos[0], $pos[1] + 1];
            }
            $move_box = false;
            $can_move = false;
            $n = json_encode($next);
            if ($dir == "<" || $dir == ">") {
                while ($map[$next[1]][$next[0]] != "#") {
                    if ($map[$next[1]][$next[0]] == "[" || $map[$next[1]][$next[0]] == "]") {
                        $move_box = true;
                    } else if ($map[$next[1]][$next[0]] == ".") {
                        if ($move_box) {
                            if ($dir == "<") {
                                for ($x=$next[0]; $x<$pos[0]; $x++) {
                                    $map[$next[1]][$x] = (($pos[0] - $x) % 2) ? "[" : "]";
                                }
                            } else if ($dir == ">") {
                                for ($x=$next[0]; $x>$pos[0]; $x--) {
                                    $map[$next[1]][$x] = (($x - $pos[0]) % 2) ? "]" : "[";
                                }
                            }
                            $can_move = true;
                            break;
                        } else {
                            $can_move = true;
                            break;
                        }
                    }
                    if ($dir == "<") {
                        $next[0]--;
                    } else if ($dir == ">") {
                        $next[0]++;
                    }

                }
            } else if ($dir == "^" || $dir == "v") {
                $boxes = [];
                if ($map[$next[1]][$next[0]] == ".") {
                    $can_move = true;
                } else if ($map[$next[1]][$next[0]] == "[") {
                    $boxes = Day15::find_box_stack($map, $dir, $next[1], [[$next[0],$next[1]]]);
                } else if ($map[$next[1]][$next[0]] == "]") {
                    $boxes = Day15::find_box_stack($map, $dir, $next[1], [[$next[0]-1,$next[1]]]);
                }
                // Logger::log(json_encode($boxes));
                if (count($boxes)) {
                    $can_move = true;
                    $m = ($dir == "^") ? -1 : 1;
                    foreach ($boxes as $bi => $box) {
                        if ($map[$box[1]+$m][$box[0]] == "#" || $map[$box[1]+$m][$box[0]+1] == "#") {
                            $can_move = false;
                        }
                    }
                    if ($can_move) {
                        $move_box = true;
                        if ($dir == "v") {
                            usort($boxes, function($a, $b) { return $b[1] - $a[1]; });
                        } else {
                            usort($boxes, function($a, $b) { return $a[1] - $b[1]; });
                        }
                        foreach ($boxes as $bi => $box) {
                            $map[$box[1]][$box[0]] = ".";
                            $map[$box[1]][$box[0]+1] = ".";
                            $map[$box[1]+$m][$box[0]] = "[";
                            $map[$box[1]+$m][$box[0]+1] = "]";
                        }
                    }
                }
            }
            
            if ($can_move) {
                $map[$pos[1]][$pos[0]] = ".";
                if ($dir == "<") {
                    $pos[0]--;
                } else if ($dir == ">") {
                    $pos[0]++;
                } else if ($dir == "^") {
                    $pos[1]--;
                } else if ($dir == "v") {
                    $pos[1]++;
                }
                $map[$pos[1]][$pos[0]] = "@";
            }
        }
        // Day15::show_map($map, count($moves), "");

        $p2 = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $spot) {
                if ($spot == "[") {
                    $p2 += (100 * $y) + $x;
                }
            }
        }
        Logger::log("Part 2: $p2");
    }

    private static function find_box_stack($map, $dir, $y, $boxes) {
        $m = ($dir == "^") ? -1 : 1;
        $more = false;
        foreach ($boxes as $i => $box) {
            if ($box[1] != $y) {
                continue;
            }
            if ($map[$box[1]+$m][$box[0]] == "]") {
                $more = true;
                array_push($boxes, [$box[0]-1, $box[1]+$m]);
            }
            if ($map[$box[1]+$m][$box[0]] == "[") {
                $more = true;
                array_push($boxes, [$box[0], $box[1]+$m]);
            }
            if ($map[$box[1]+$m][$box[0]+1] == "[") {
                $more = true;
                array_push($boxes, [$box[0]+1, $box[1]+$m]);
            }
        }
        return (($more) ? Day15::find_box_stack($map, $dir, $y+$m, $boxes) : $boxes);
    }

    private static function show_map($map, $i, $d) {
        Logger::log("Map after move $i ($d)");
        foreach ($map as $y => $row) {
            Logger::log(join('', $row));
        }
    }
}