<?php

namespace App\Service;

class PokemonSorter
{
    public function getPokemonSorter(&$arr, $col, $dir = SORT_ASC): array
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);

        return $arr;
    }
}
