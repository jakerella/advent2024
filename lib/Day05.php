<?php

namespace Advent;

use Advent\Logger;

class Day05
{   
    public function run($lines) {
        $rules = [];
        $updates = [];
        foreach ($lines as $line_num => $line) {
            $rule = preg_split('/\|/', $line);
            if (count($rule) == 2) {
                if (!isset($rules[$rule[0]])) {
                    $rules[$rule[0]] = [];
                }
                array_push($rules[$rule[0]], $rule[1]);
            } else {
                $update = preg_split('/\,/', $line);
                if (count($update) > 2) {
                    array_push($updates, $update);
                }
            }
        }

        $bad_updates = [];
        $p1_correct_sum = 0;
        foreach ($updates as $ui => $update) {
            $good = true;
            foreach ($update as $pos => $num) {
                if (isset($rules[$num]) && $pos > 0) {
                    for ($i = 0; $i < $pos; $i++) {
                        $found = array_search($update[$i], $rules[$num]);
                        if ($found === 0 || $found != false) {
                            $good = false;
                            array_push($bad_updates, $update);
                            break;
                        }
                    }
                    if (!$good) {
                        break;
                    }
                }
            }
            if ($good) {
                $middle = $update[ceil(count($update) / 2) - 1];
                $p1_correct_sum += $middle;
            }
        }
        Logger::log("Part 1: $p1_correct_sum");

        $p2_correct_sum = 0;
        foreach ($bad_updates as $ui => $update) {
            $fixed = Day05::fix_update($rules, $update);
            $middle = $fixed[ceil(count($fixed) / 2) - 1];
            $p2_correct_sum += $middle;
        }
        Logger::log("Part 2: $p2_correct_sum");
    }

    public static function fix_update($rules, $update) {
        $found_bad = false;
        $fixed = [];
        foreach ($update as $pos => $num) {
            array_push($fixed, $num);
            if (isset($rules[$num]) && $pos > 0) {
                for ($i = 0; $i < $pos; $i++) {
                    $found = array_search($fixed[$i], $rules[$num]);
                    if ($found === 0 || $found != false) {
                        $found_bad = true;
                        array_push($fixed, array_splice($fixed, $i, 1)[0]);
                    }
                }
            }
        }
        if ($found_bad) {
            return Day05::fix_update($rules, $fixed);
        }
        return $fixed;
    }
}