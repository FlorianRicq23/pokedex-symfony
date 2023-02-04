<?php

namespace App\Command;

use App\Service\PokeClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:export-pokemons-csv',
    description: 'export CSV list of all pokemons ',
)]
class ExportPokemonsCsvCommand extends Command
{
    /** @var PokeClient */
    protected $pokeClient;

    /**
     * RunCommand constructor.
     * @param PokeClient $pokeClient
     */
    public function __construct(PokeClient $pokeClient)
    {
        $this->pokeClient = $pokeClient;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Exports pokemons to a CSV file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = 'pokedex.csv';

        $application = $this->getApplication();
        $container = $application->getKernel()->getContainer();
        $generation = $container->getParameter('POKEMON_GENERATION');

        $pokemonGeneration = $this->pokeClient->getPokemonGeneration($generation);

        $pokemonCsv = [['id', 'name', 'height', 'weight']];
        $pokemonGenerationList = $pokemonGeneration['pokemon_species'];

        foreach ($pokemonGenerationList as $pokemon) {
            $pokemonDetails = $this->pokeClient->getPokemonsCsv($pokemon['name']);
            $pokemonDetails != [] ? $pokemonCsv[] = $pokemonDetails : null;
        }

        $handle = fopen($file, 'w');

        if (!$handle) {
            $output->writeln(sprintf('Unable to create file %s', $file));
            return 1;
        }

        foreach ($pokemonCsv as $fields) {
            fputcsv($handle, $fields);
        }

        fclose($handle);

        $output->writeln(sprintf('pokemonCsv exported to %s', $file));

        return 0;
    }
}