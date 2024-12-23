<?php

namespace Advent;

use Advent\Logger;

class Day23
{
    public function run($lines) {
        $net = [];
        foreach ($lines as $i => $line) {
            $c = preg_split('/\-/', $line);
            if (!isset($net[$c[0]])) {
                $net[$c[0]] = [];
            }
            if (!isset($net[$c[1]])) {
                $net[$c[1]] = [];
            }
            array_push($net[$c[0]], $c[1]);
            array_push($net[$c[1]], $c[0]);
        }

        $sets = [];
        $groups = [];
        foreach ($net as $m => $c) {
            // Logger::log("$m: ".json_encode($c));
            $group = [$m];
            for ($i=0; $i<count($c); $i++) {
                
                // part 2
                $connected = true;
                foreach ($group as $gi => $gm) {
                    if (array_search($gm, $net[$c[$i]]) === false) {
                        $connected = false;
                    }
                }
                if ($connected) {
                    array_push($group, $c[$i]);
                }

                // part 1
                for ($j=0; $j<count($c); $j++) {
                    if ($i == $j) { continue; }
                    $set_a = [$m, $c[$i], $c[$j]];
                    sort($set_a);
                    $set = join(',', $set_a);
                    // Logger::log("Checking set: $set");
                    if (!isset($sets[$set]) && array_search($c[$i], $net[$c[$j]]) !== false) {
                        // Logger::log("*** Found new set: $set");
                        $sets[$set] = 1;
                    }
                }
            }
            sort($group);
            if (!isset($groups[join(',',$group)])) {
                $groups[join(',',$group)] = 1;
            }
        }
        
        $p1 = 0;
        foreach ($sets as $s => $_) {
            if (preg_match('/^t|\,t/', $s)) {
                $p1++;
            }
        }
        Logger::log("Part 1: $p1");

        $p2 = "";
        foreach ($groups as $g => $_) {
            if (strlen($g) > strlen($p2)) {
                $p2 = $g;
            }
        }
        Logger::log("Part 2: $p2");
    }
}