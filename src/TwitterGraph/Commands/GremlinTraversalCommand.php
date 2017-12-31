<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands;

use Brightzone\GremlinDriver\InternalException;
use Brightzone\GremlinDriver\ServerException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;
use TheDonHimself\GremlinOGM\GraphConnection;

class GremlinTraversalCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('twittergraph:gremlin:traversal')
            ->setDescription('TwitterGraph Gremlin Traversal Command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Send A Gremlin Command');

        $configPath = $io->ask('Enter the path to a yaml configuration file or use defaults (JanusGraph, localhost:8182 with ssl, no username or password)', null, function ($input_path) {
            return $input_path;
        });

        $gremlin_command = $io->ask('Enter the gremlin traversal', null, function ($input_command) {
            if (null === $input_command) {
                throw new RuntimeException('You need to type a command.');
            }

            return $input_command;
        });

        $options = GraphConnection::DEFAULT_OPTIONS;

        $vendor = array();

        if ($configPath) {
            $config = Yaml::parseFile($configPath);
            $options = $config['options'];
            $vendor = $config['vendor'] ?? array();
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

        $output->writeln('Sending Command...');

        try {
            $command_start_time = microtime(true);
            $resultSet = $graph_connection->send($gremlin_command);
            $command_finish_time = microtime(true);
            $command_time = $command_finish_time - $command_start_time;
        } catch (ServerException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $graph_connection->close();

        $output->writeln(print_r($resultSet));

        $output->writeln('Command Took Appoximately : '.$command_time);
    }
}
