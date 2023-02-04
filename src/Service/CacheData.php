<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheData
{
    public function getCachePokemonGenerationList($generation, $pokeClient): array
    {
        $cache = new FilesystemAdapter();
        $cachePokemonGenerationList = $cache->getItem('pokemon_generation');
        if (!$cachePokemonGenerationList->isHit()) {
            $pokemonGeneration = $pokeClient->getPokemonGeneration($generation);
            $pokemonDetailsList = [];
            $pokemonGenerationList = $pokemonGeneration['pokemon_species'];
            foreach ($pokemonGenerationList as $pokemon) {
                $pokemonDetails = $pokeClient->getPokemonDetails($pokemon['name']);
                $pokemonDetails != [] ? $pokemonDetailsList[] = $pokemonDetails : null;
            }
            $cachePokemonGenerationList->set($pokemonDetailsList);
            $cache->save($cachePokemonGenerationList);
        }
        return $cachePokemonGenerationList->get();
    }
}
