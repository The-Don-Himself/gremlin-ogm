<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands;

use Brightzone\GremlinDriver\InternalException;
use Brightzone\GremlinDriver\ServerException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TheDonHimself\GremlinOGM\GraphConnection;

class VertexesDropCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('twittergraph:vertexes:drop')
            ->setDescription('TwitterGraph Drop Vertexes')
            ->addOption('configPath', null, InputOption::VALUE_OPTIONAL, 'The Path to the JSON Configuration FIle')
            ->addOption('label', null, InputOption::VALUE_OPTIONAL, 'The Vertex label to be deleted');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configPath = $input->getOption('configPath');

        $options = array();
        $vendor = array();

        if ($configPath) {
            $configFile = file_get_contents($configPath);
            $config = json_decode($configFile, true);
            $options = $config['options'];
            $vendor = isset($config['vendor']) ? $config['vendor'] : array();
        }

        $label = $input->getOption('label');

        if ($label) {
            $gremlin_command = 'g.V().hasLabel("'.$label.'").drop().iterate();';
        } else {
            $gremlin_command = 'g.V().drop().iterate();';
        }

        if ($vendor) {
            $vendor_name = $vendor['name'];
            $graph_name = $vendor['graph'] ?? null;

            if ('compose' === $vendor_name) {
                $gremlin_command = 'def graph = ConfiguredGraphFactory.open("'.$graph_name.'"); def g = graph.traversal(); '.$gremlin_command;
            }
            if ('azure' === $vendor_name) {
                $gremlin_command = 'g.V().drop();';
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

        $output->writeln('Dropping All Vertexes');

        try {
            $graph_connection->send($gremlin_command, 'session');
        } catch (ServerException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $graph_connection->close();

        $output->writeln('All Vertexes Dropped Successfully!');
    }
}
