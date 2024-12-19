<?php

namespace Advent;

use Advent\Logger;

class Day19
{
    public function run($lines) {
        $p1 = 0;
        $towels = [];
        $max_length = 0;
        foreach ($lines as $i => $line) {
            if (preg_match('/\,/', $line)) {
                foreach(preg_split('/\,\s/', $line) as $i => $t) {
                    if (strlen($t) > $max_length) {
                        $max_length = strlen($t);
                    }
                    $towels[$t] = 1;
                }
            } else if (strlen($line)) {
                // Logger::log("*** CHECK PATTERN: $line");
                if (Day19::match_pattern(str_split($line), $towels, $max_length, 0, [], [])) {
                    $p1++;
                } else {
                    // Logger::log("$line");
                }
            }
        }

        Logger::log("Part 1: $p1");

        $p2 = 0;
        Logger::log("Part 2: $p2");
    }

    private static function match_pattern($pattern, $towels, $max, $pos, $prev_pos, $tried) {
        $l = count($pattern);
        $m = "";
        for ($i=$pos; $i<count($pattern); $i++) {
            $m .= $pattern[$i];
            if (strlen($m) > $max) {
                break;
            }
            // Logger::log("trying to find towel with pattern $m (pos=$pos, i=$i)");
            if (isset($towels[$m])) {
                // Logger::log("matched towel with pattern $m");
                if ($i >= count($pattern)-1) {
                    // Logger::log("all matched!");
                    return true;
                }
                if (!isset($tried[$pos][$m])) {
                    $tried[$pos][$m] = 1;
                    $next = $i+1;
                    array_push($prev_pos, $pos);
                    // Logger::log("recursing with pos=$next (<$l) after matching sub-pattern: $m");
                    return Day19::match_pattern($pattern, $towels, $max, $next, $prev_pos, $tried);
                } else {
                    // Logger::log("already tried matching pos $pos to $m");
                    continue;
                }
            }
        }

        if ($pos > 0) {
            if (count($prev_pos) < 1) {
                // Logger::log("backtracked as far as I can, no match");
                return false;
            }
            $prev = array_pop($prev_pos);
            // Logger::log("not fully matched, backtracking to pos=$prev");
            return Day19::match_pattern($pattern, $towels, $max, $prev, $prev_pos, $tried);
        }
        return false;
    }
}