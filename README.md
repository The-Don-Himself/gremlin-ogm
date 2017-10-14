# gremlin-ogm
A PHP Object Graph Mapper for Tinkerpop 3+ compatible Graph Databases (JanusGraph, Neo4j, etc.) that allows you to persist data and run gremlin queries.

## Note: Still a work in progress. I'm progressively open sourcing the code I use in production with JanusGraph. If you use Symfony, then there's a bundle I'm finishing on that will allow you to map Doctrine ORM entities from a compatible RDBMS database as well as Doctrine ODM documents from MongoDB to a Graph Database.


### Usage
Configure a Graph connection. This is simply a proxy to the underlying gremlin-server client for php aka brightzone/gremlin-php so you can check out the connection Class for configuration defaults and options Brightzone\GremlinDriver\Connection. For testing and development, I suggest something like the below

````
$options = [
    'host' => 127.0.0.1, 
    'port' => 8182, 
    'username' => null, 
    'password' => null, 
    'ssl' = [ 
      'ssl_verify_peer' => false, 
      'ssl_verify_peer_name' => false
    ],
    'graph' => 'graph', 
    'timeout' => 10, 
    'emptySet' => true,
    'retryAttempts' => 3
];
````

After configuring options, initiate a Graph connection

````
$graph_connection = (new \TheDonHimself\GremlinOGM\GraphConnection($options))->init();
````

At this point you can open a connection to the Graph database and send gremlin-queries to it as shown.

````
// Open Connection
$graph_connection->open();

// Query Number of Vertices in Graph
$resultSet = $graph_connection->send('g.V().count()');

// Close Connection
$graph_connection->close();
````

Again these are just proxies to the underlying brightzone/gremlin-php library, so what does gremlin-ogm do? Gremlin-OGM helps you map PHP objects to Graph Vertexes, Edges, Properties and Indexes. It also provides a way to validate that mapping and point out areas where the schema fails.

Check out the TwitterGraph folder for an elaborate example of how to use gremlin-ogm to graph Twitter in a Graph Database.
