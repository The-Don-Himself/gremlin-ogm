<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands;

use Brightzone\GremlinDriver\InternalException;
use Brightzone\GremlinDriver\ServerException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TheDonHimself\GremlinOGM\GraphConnection;

class EdgesCountCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('twittergraph:edges:count')
            ->setDescription('Count Number Of Edges in TwitterGraph')
            ->addOption('configPath', null, InputOption::VALUE_OPTIONAL, 'The Path to the JSON Configuration FIle')
            ->addOption('label', null, InputOption::VALUE_OPTIONAL, 'The Edge label to be counted');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configPath = $input->getOption('configPath');

        $options = array();

        if ($configPath) {
            $configFile = file_get_contents($configPath);
            $config = json_decode($configFile, true);
            $options = $config['options'];
        }

        $label = $input->getOption('label');

        $graph_connection = (new GraphConnection($options))->init();

        try {
            $graph_connection->open();
        } catch (InternalException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $gremlin_command = 'g.E().count()';

        try {
            $resultSet = $graph_connection->send($gremlin_command);
            $number_of_edges = $resultSet[0];
            $output->writeln('Number of Edges In TwitterGraph : '.number_format($number_of_edges));
        } catch (ServerException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $graph_connection->close();
    }
}
