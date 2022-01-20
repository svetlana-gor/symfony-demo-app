<?php

namespace App\Command\ProductCommand;

use Symfony\Component\Console\Command\Command;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\NullOutput;
use Doctrine\ORM\Mapping\ClassMetadata;
use App\Entity\Product;
use App\Entity\ProductImage;

/**
 * Creates Product and ProductImage entities with relations and stores them in a database.
 */
class DataLoadCommand extends Command
{
    protected static $defaultName = 'data:load';
    protected static $defaultDescription = 'Creates products and images with relations and stores them in a database.';
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();

        $this->doctrine = $doctrine;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $arguments = [
            '--group' => ['DataLoadGroup'],
            '--purge-exclusions' => $this->getTablesToIgnoreWhilePurging(),
        ];

        $dataLoadInput = new ArrayInput($arguments);
        $dataLoadInput->setInteractive(false);

        $ui = new SymfonyStyle($input, $output);
        $ui->success('Success!');
        $ui->writeln([' <comment>></comment> <info>Products and images have been stored in the database.</info>', '']);

        $command->run($dataLoadInput, new NullOutput());

        return Command::SUCCESS;
    }

    /**
     * Gets an array of all database tables except for the Product and ProductImage entities tables.
     *
     * @return array
     */
    private function getTablesToIgnoreWhilePurging(): array
    {
        $em = $this->doctrine->getManager();

        $allMetadata = $em->getMetadataFactory()->getAllMetadata();
        $allTables = array_map(
            function(ClassMetadata $metadata) {
                return $metadata->getTableName();
            },
            $allMetadata);

        $tablesNotToIgnore = [
            $em->getClassMetadata(Product::class)->getTableName(),
            $em->getClassMetadata(ProductImage::class)->getTableName(),
        ];

        return array_diff($allTables, $tablesNotToIgnore);
    }
}
