<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands;

use Brightzone\GremlinDriver\InternalException;
use Brightzone\GremlinDriver\ServerException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;
use TheDonHimself\GremlinOGM\GraphConnection;

class VertexesCountCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('twittergraph:vertexes:count')
            ->setDescription('Count Number Of Vertexes in TwitterGraph');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Count Vertexes in the Graph');

        $configPath = $io->ask('Enter the path to a yaml configuration file or use defaults (JanusGraph, 127.0.0.1:8182 with ssl, no username or password)', null, function ($input_path) {
            return $input_path;
        });

        $label = $io->ask('Enter an optional vertex label to count or leave blank to count all vertexes', null, function ($input_label) {
            return $input_label;
        });

        $options = GraphConnection::DEFAULT_OPTIONS;

        $vendor = array();

        if ($configPath) {
            $config = Yaml::parseFile($configPath);
            $options = $config['options'];
            $vendor = $config['vendor'] ?? array();
        }

        if ($label) {
            $gremlin_command = 'g.V().hasLabel("'.$label.'").count();';
        } else {
            $gremlin_command = 'g.V().count();';
        }

        if ($vendor) {
            $vendor_name = $vendor['name'];
            $graph_name = $vendor['graph'] ?? null;

            if ('compose' === $vendor_name) {
                $gremlin_command = 'def graph = ConfiguredGraphFactory.open("'.$graph_name.'"); def g = graph.traversal(); '.$gremlin_command;
            }
        }

        $graph = (new GraphConnection($options))->init();
        $graph_connection = $graph->getConnection();

        try {
            $graph_connection->open();
        } catch (InternalException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        try {
            $resultSet = $graph_connection->send($gremlin_command, 'session');
            $number_of_vertexes = $resultSet[0];
            $output->writeln('Number of Vertexes In TwitterGraph : '.number_format($number_of_vertexes));
        } catch (ServerException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $graph_connection->close();
    }
}
