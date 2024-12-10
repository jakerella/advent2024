<?php

namespace Advent;

use Advent\Logger;

class DayXX
{   
    public function run($input) {
        Logger::log("Day XX Start");
        $lines = file($input);

        $p1 = 0;
        foreach ($lines as $line_num => $line) {
            $stuff = preg_split('/\n/', $line);
            if (count($stuff) !== 2) { continue; } // last line
            $input = $stuff[0];


        }

        Logger::log("Part 1: $p1");

        // Logger::log("Part 2: $p2");
    }
}