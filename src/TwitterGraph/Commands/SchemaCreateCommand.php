<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands;

use Brightzone\GremlinDriver\InternalException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TheDonHimself\GremlinOGM\GraphConnection;
use TheDonHimself\GremlinOGM\Tools\BuildClassMaps;
use TheDonHimself\GremlinOGM\Tools\SchemaCheck;
use TheDonHimself\GremlinOGM\Tools\SchemaCreate;

class SchemaCreateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('twittergraph:schema:create')
            ->setDescription('TwitterGraph Schema Create')
            ->addOption('configPath', null, InputOption::VALUE_OPTIONAL, 'The Path to the JSON Configuration FIle');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $twitterGraphPath = dirname(dirname(__FILE__));

        $class_maps = (new BuildClassMaps())->build($twitterGraphPath);
        $schema = (new SchemaCheck())->check($class_maps);
        $commands = (new SchemaCreate())->create($schema);

        $configPath = $input->getOption('configPath');

        $options = array();
        $vendor = array();

        if ($configPath) {
            $configFile = file_get_contents($configPath);
            $config = json_decode($configFile, true);
            $options = $config['options'];
            $vendor = isset($config['vendor']) ? $config['vendor'] : array();
        }

        $graph_connection = (new GraphConnection($options))->init();

        try {
            $graph_connection->open();
        } catch (InternalException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $output->writeln('Creating Schema...');

        if ($vendor) {
            $vendor_name = $vendor['name'];
            $graph_name = $vendor['graph'];

            if ('compose' === $vendor_name) {
                foreach ($commands as $key => &$value) {
                    if (false !== strpos($value, '=')) {
                        $value = 'def '.$value;
                    }
                    if (false !== strpos($value, 'buildMixedIndex')) {
                        unset($commands[$key]);
                    }
                }

                $command_string = 'ConfiguredGraphFactory.create("'.$graph_name.'"); def graph = ConfiguredGraphFactory.open("'.$graph_name.'"); def mgmt = graph.openManagement(); null;';

                $graph_connection->send($command_string, 'session');
            }
        } else {
            $graph_connection->send('mgmt = graph.openManagement();null');
        }

        $graph_connection->transaction(function (&$graph_connection, $commands) {
            foreach ($commands as $command) {
                $graph_connection->send($command, 'session');
            }
            $graph_connection->send('mgmt.commit()');
        }, [&$graph_connection, $commands]);

        $graph_connection->close();

        $output->writeln('TwitterGraph Schema Created Successfully!');
    }
}
