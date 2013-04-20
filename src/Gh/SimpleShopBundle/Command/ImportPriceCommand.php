<?php

namespace Gh\SimpleShopBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPriceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import:price')
            ->setDescription('Import xml price from root of project')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of price from root of project', 'price.xml')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $progress = $this->getHelperSet()->get('progress');
        $importer = $this->getContainer()->get('gh.simple_shop.import_price');

        $importer->importPriceFile($name);
        $count = $importer->getNodesCount();

        $progress->start($output, $count+1);

        for ($i=0; $i < $count; $i++) {
            $importer->importNode($i);
            $importer->nextNode();

            $progress->advance();
        }

        $importer->flush();
        $progress->advance();

        $progress->finish();

        $output->writeln('Price is imported successfully!');
    }
}