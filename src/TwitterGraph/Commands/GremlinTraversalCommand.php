<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands;

use Brightzone\GremlinDriver\InternalException;
use Brightzone\GremlinDriver\ServerException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TheDonHimself\GremlinOGM\GraphConnection;

class GremlinTraversalCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('twittergraph:gremlin:traversal')
            ->setDescription('TwitterGraph Gremlin Traversal Command')
            ->addArgument('traversal', InputArgument::REQUIRED, 'The Gremlin Traversal to Send')
            ->addOption('configPath', null, InputOption::VALUE_OPTIONAL, 'The Path to the JSON Configuration FIle');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = new \TheDonHimself\GremlinOGM\TwitterGraph\Repository\TweetsRepository();
        $command = $repo->getTimelineForScreenName('the_don_himself');

        return;

        $gremlin_command = $input->getArgument('traversal');
        $configPath = $input->getOption('configPath');

        $options = array();
        $vendor = array();

        if ($configPath) {
            $configFile = file_get_contents($configPath);
            $config = json_decode($configFile, true);
            $options = $config['options'];
            $vendor = isset($config['vendor']) ? $config['vendor'] : array();
        }

        if ($vendor) {
            $vendor_name = $vendor['name'];
            $graph_name = $vendor['graph'];

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

        $output->writeln('Sending Command...');

        try {
            $resultSet = $graph_connection->send($gremlin_command);
        } catch (ServerException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $graph_connection->close();

        $output->writeln(print_r($resultSet));
    }
}
