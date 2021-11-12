<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use App\Service\VehicleFixer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DropMileagePercent extends Command
{
    public const PAGE_SIZE = 100;

    protected static $defaultName = 'app:vehicle:drop-mileage-percent';

    protected EntityManagerInterface $em;
    protected VehicleRepository $repository;
    protected VehicleFixer $fixer;

    public function __construct(EntityManagerInterface $em, VehicleFixer $fixer)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(Vehicle::class);
        $this->fixer = $fixer;
    }

    protected function configure()
    {
        $this->setDescription('Уменьшение пробега всем машинам с заданным пробегом пробегом на заданный процент');
        $this->addOption(
            'mileage',
            'mil',
            InputOption::VALUE_OPTIONAL,
            'Пробег, больше которого надо уже уменьшать',
            150000
        )->addOption(
            'percent',
            'per',
            InputOption::VALUE_OPTIONAL,
            'Процент уменьшения',
            30
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $io = new SymfonyStyle($input, $output);

        $criticalMileage = (int)$input->getOption('mileage');
        if (!$criticalMileage || $criticalMileage < 0) {
            $io->error('Некорректный пробег!');
            return Command::INVALID;
        }

        $dropPercent = (int)$input->getOption('percent');
        if (!$dropPercent || $dropPercent < 0 || $dropPercent > VehicleFixer::MILEAGE_MAX_DROP_PERCENT) {
            $io->error('Некорректный процент уменьшения!');
            return Command::INVALID;
        }

        $output->writeln('<info>Обработка начата</info>');

        $offset = 0;
        $total = $this->repository->getVehicleWithMileageCount($criticalMileage);
        $progressBar = new ProgressBar($output, $total);
        $progressBar->setBarCharacter('<fg=green>=</>');
        $progressBar->display();

        do {
            $vehicles = $this->repository->getVehicleWithMileagePaginated($criticalMileage, self::PAGE_SIZE, $offset);

            foreach ($vehicles as $vehicle) {
                $this->fixer->dropMileage($vehicle,$dropPercent, VehicleFixer::MILEAGE_PERCENT);
                $progressBar->advance();
            }

            $this->em->flush();
            $offset += count($vehicles);

        } while (count($vehicles));

        $progressBar->finish();
        $io->newLine();
        $io->success('Обработка окончена');

        return Command::SUCCESS;
    }
}