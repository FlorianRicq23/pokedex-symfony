<?php

namespace App\Controller;

use App\Service\PokeClient;
use App\Service\PokemonSorter;
use App\Service\CacheData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class PokemonController extends AbstractController
{
    #[Route('/pokemon/{sort_select}', name: 'app_pokemon')]
    public function index(Request $request, PaginatorInterface $paginator, PokeClient $pokeClient, PokemonSorter $pokemonSorter, CacheData $cacheData, string $sort_select = 'id'): Response
    {
        $generation = $this->getParameter('POKEMON_GENERATION');

        $pokemonGeneration = $cacheData->getCachePokemonGenerationList($generation, $pokeClient);

        $pokemonGenerationSorted = $pokemonSorter->getPokemonSorter($pokemonGeneration, $sort_select);

        $pokemonGenerationPaginated = $paginator->paginate(
            $pokemonGenerationSorted,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('pokemon/index.html.twig', [
            'pokemonGenerationPaginated' => $pokemonGenerationPaginated
        ]);
    }

    #[Route('/pokemon-details/{name}', name: 'show_pokemon')]
    public function show(PokeClient $pokeClient, $name): Response
    {
        $pokemonDetails = $pokeClient->getPokemonDetails($name);

        return $this->render('pokemon/show.html.twig', [
            'pokemonDetails' => $pokemonDetails
        ]);
    }
}
