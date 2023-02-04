<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokeClient
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getPokemonGeneration($generation)
    {
        $response = $this->client->request('GET', 'https://pokeapi.co/api/v2/generation/' . $generation);
        $statusCode = $response->getStatusCode();
        if ($statusCode == 404) 
        {
            return array();
        }
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
    public function getPokemonDetails($name): array
    {
        $response = $this->client->request('GET', 'https://pokeapi.co/api/v2/pokemon/' . $name);
        $statusCode = $response->getStatusCode();
        if ($statusCode == 404) 
        {
            return array();
        }
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
}
