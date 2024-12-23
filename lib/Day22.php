<?php

namespace Advent;

use Advent\Logger;

class Day22
{
    public function run($lines) {
        $buyers = array_map(function($n) { return (int) $n; }, $lines);
        // $buyers = [123];
        // logger::log(json_encode($buyers));
        $count = 2000;
        $change_sets = [];
        foreach ($buyers as $bi => $s) {
            $ns = $s;
            $changes = [];
            $seen = [];
            for ($i=0; $i<$count; $i++) {
                $last = (int) substr("$ns", -1);
                
                $ns = Day22::next($ns);
                
                if (count($changes) > 3) { array_shift($changes); }
                $curr = (int) substr("$ns", -1);
                array_push($changes, $curr - $last);

                if ($i > 2) {
                    $diffs = join(',', $changes);
                    if (!isset($seen[$diffs])) {
                        $seen[$diffs] = 1;
                        if (!isset($change_sets[$diffs])) {
                            $change_sets[$diffs] = [];
                        }
                        array_push($change_sets[$diffs], $curr);
                    }
                }
            }
            $buyers[$bi] = [$s, $ns];
            // Logger::log("$s => $ns");
        }

        $p1 = array_reduce($buyers, function($prev, $curr) { return $prev + $curr[1]; });
        Logger::log("Part 1: $p1");


        $p2 = 0;
        // Logger::log(json_encode($change_sets));
        foreach ($change_sets as $set => $prices) {
            $total = array_reduce($prices, function($prev, $curr) { return $prev + $curr; });
            if ($total > $p2) {
                // Logger::log("Found new high with $set = $total");
                $p2 = $total;
            }
        }
        Logger::log("Part 2: $p2");
    }

    private static function next($s) {
        $ns = (($s * 64) ^ $s) % 16777216;
        $ns = (floor($ns / 32) ^ $ns) % 16777216;
        $ns = (($ns * 2048) ^ $ns) % 16777216;
        return $ns;

        // Calculate the result of multiplying the secret number by 64. 
        // Then, mix this result into the secret number.
        // Finally, prune the secret number.
        // Calculate the result of dividing the secret number by 32. 
        // Round the result down to the nearest integer. 
        // Then, mix this result into the secret number. Finally, prune the secret number.
        // Calculate the result of multiplying the secret number by 2048. 
        // Then, mix this result into the secret number. Finally, prune the secret number.

        // To mix a value into the secret number, calculate the bitwise XOR of the given value 
        // and the secret number. Then, the secret number becomes the result of that operation. 
        // (If the secret number is 42 and you were to mix 15 into the secret number, the secret 
        // number would become 37.)
        // To prune the secret number, calculate the value of the secret number modulo 16777216. 
        // Then, the secret number becomes the result of that operation. 
        // (If the secret number is 100000000 and you were to prune the secret number, the secret 
        // number would become 16113920.)
    }
}