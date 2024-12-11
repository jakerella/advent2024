<?php

namespace Advent;

use Advent\Logger;

class Day09
{   
    public function run($lines) {
        $disk = str_split($lines[0]);
        
        $blocks = [];
        $files = [];
        $free = [];
        $next_id = 0;
        $file = true;
        foreach ($disk as $i => $block) {
            if ($file) {
                array_push($files, [$next_id, (int) $block, count($blocks)]);  // part 2
                $blocks = array_merge($blocks, array_fill(0, $block, $next_id));
                $next_id++;
                $file = false;
            } else {
                array_push($free, [count($blocks), (int) $block]);  // part 2
                $blocks = array_merge($blocks, array_fill(0, $block, '.'));
                $file = true;
            }
        }

        // part 1
        $defrag = [];
        for ($i = 0; $i < count($blocks); $i++) {
            if ($blocks[$i] == '.') {
                for ($j = count($blocks)-1; $j > $i; $j--) {
                    if ($blocks[$j] != '.') {
                        array_push($defrag, $blocks[$j]);
                        array_splice($blocks, $j, 1);
                        break;
                    }
                }
            } else {
                array_push($defrag, $blocks[$i]);
            }
        }
        $p1 = 0;
        foreach ($defrag as $i => $id) {
            $p1 += ($i * $id);
        }

        Logger::log("p1: $p1");


        // part 2
        for ($i = count($files)-1; $i > 0; $i--) {
            for ($fi = 0; $fi < count($free); $fi++) {
                $index = $free[$fi][0];
                $size = $free[$fi][1];
                if ($index < $files[$i][2] && $size >= $files[$i][1]) {
                    $files[$i][2] = $index;
                    $new_free_size = $size - $files[$i][1];
                    array_splice($free, $fi, 1, [[$index + $files[$i][1], $new_free_size]]);
                    break;
                }
            }
        }

        $p2 = 0;
        foreach ($files as $i => $file) {
            for ($i = 0; $i < $file[1]; $i++) {
                $p2 += ($file[0] * ($file[2] + $i));
            }
        }

        Logger::log("p2: $p2");
    }
}