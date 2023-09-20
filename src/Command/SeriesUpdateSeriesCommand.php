<?php

namespace App\Command;

use App\Helper\UpdateSerie;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'series:update-series',
    description: 'Met à jour le catalogue',
)]
class SeriesUpdateSeriesCommand extends Command
{
    public function __construct(private UpdateSerie $updater, string $name = null) {
        parent:: __construct($name);
    }
    protected function configure(): void
    {
        $this
            ->addArgument('genre', InputArgument::OPTIONAL, 'Seules les séries de ce genre vont être mises à jour')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $genre = $input->getArgument('genre');

        if ($genre) {
            $io->note(sprintf('Seules les séries de ce genre: %s', $genre));
        }

        try {
            $cpt = $this->updater->removeOldSeries($genre, $input->getOption('force') );
        } catch(\Exception $e) {
            return Command::FAILURE;
        }


        if (!$input->getOption('force')) {
            $io->note(sprintf('Il y\'a %d series correspondantes aux critères de suppression.  Executez -f pour effectuer la suppression', $cpt));
        } else {
            $io->success(sprintf('%d séries supprimées', $cpt));
        }

        /**
        $io->text('Salut');

        $response = $io->ask('Comment ça va ?', 'Pas top');

        $io->confirm('C\'est sûr ?', false);

        $response2 = $io->choice("Parfum de la glace ?", ['vanille', 'fraise', 'chocolat']);

        $io->error('Y\'en a plus');

        $io->writeln("Ah si ! y'en a");

        if (true === false) {
            return Command::FAILURE;
        }

        $io->success('Tout s\'est bien passé.');

         * **/



        return Command::SUCCESS;
    }
}
