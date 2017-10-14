<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TheDonHimself\GremlinOGM\Tools\BuildClassMaps;
use TheDonHimself\GremlinOGM\Tools\SchemaCheck;

class SchemaCheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('twittergraph:schema:check')
            ->setDescription('TwitterGraph Schema Check');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $twitterGraphPath = dirname(dirname(__FILE__));

        $class_maps = (new BuildClassMaps())->build($twitterGraphPath);
        (new SchemaCheck())->check($class_maps);

        $output->writeln('Graph Schema Check Completed Successfully');
    }
}
