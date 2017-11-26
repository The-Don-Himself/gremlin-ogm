<?php

use Symfony\Component\Console\Application;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands\EdgesCountCommand;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands\EdgesDropCommand;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands\GremlinTraversalCommand;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands\SchemaCheckCommand;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands\SchemaCreateCommand;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands\PopulateCommand;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands\VertexesCountCommand;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands\VertexesDropCommand;

$autoloadFiles = array(__DIR__ . '/../vendor/autoload.php',
                       __DIR__ . '/../../../autoload.php');

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
    }
}

$application = new Application();

$application->add(new EdgesCountCommand());
$application->add(new EdgesDropCommand());
$application->add(new GremlinTraversalCommand());
$application->add(new SchemaCheckCommand());
$application->add(new SchemaCreateCommand());
$application->add(new PopulateCommand());
$application->add(new VertexesCountCommand());
$application->add(new VertexesDropCommand());

$application->run();
