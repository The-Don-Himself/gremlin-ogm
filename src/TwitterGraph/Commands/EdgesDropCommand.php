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

class EdgesDropCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('twittergraph:edges:drop')
            ->setDescription('TwitterGraph Drop Edges');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Drop Edges in the Graph');

        $configPath = $io->ask('Enter the path to a yaml configuration file or use defaults (JanusGraph, 127.0.0.1:8182 with ssl, no username or password)', null, function ($input_path) {
            return $input_path;
        });

        $label = $io->ask('Enter an optional edge label to drop or leave blank to drop all edges', null, function ($input_label) {
            return $input_label;
        });

        $options = GraphConnection::DEFAULT_OPTIONS;

        $vendor = array();

        if ($configPath) {
            $config = Yaml::parseFile($configPath);
            $options = $config['options'];
            $vendor = $config['vendor'] ?? array();
        }

        $label = $input->getOption('label');

        if ($label) {
            $gremlin_command = 'g.E().hasLabel("'.$label.'").drop().iterate();';
        } else {
            $gremlin_command = 'g.E().drop().iterate();';
        }

        if ($vendor) {
            $vendor_name = $vendor['name'];
            $graph_name = $vendor['graph'];

            if ('compose' === $vendor_name) {
                $gremlin_command = 'def graph = ConfiguredGraphFactory.open("'.$graph_name.'"); def g = graph.traversal(); '.$gremlin_command;
            }
            if ('azure' === $vendor_name) {
                $gremlin_command = 'g.E().drop();';
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

        $output->writeln('Dropping All Edges');

        try {
            $graph_connection->send($gremlin_command);
        } catch (ServerException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $graph_connection->close();

        $output->writeln('All Edges Dropped Successfully!');
    }
}
