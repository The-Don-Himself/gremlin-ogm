# gremlin-ogm
A PHP Object Graph Mapper for Tinkerpop 3+ compatible Graph Databases (JanusGraph, Neo4j, etc.) that allows you to persist data and run gremlin queries.

Check out the TwitterGraph folder for an elaborate example of how to use gremlin-ogm to graph Twitter in a Graph Database.


## Usage
Configure a Graph connection. This is simply a proxy to the underlying gremlin-server client for php aka [brightzone/gremlin-php](https://github.com/PommeVerte/gremlin-php) so you can check out the connection Class for configuration defaults and options Brightzone\GremlinDriver\Connection. For testing and development, I suggest something like the below

````
$options = [
    'host' => '127.0.0.1', 
    'port' => 8182, 
    'username' => null, 
    'password' => null, 
    'ssl' => [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ],
    'graph' => 'graph', 
    'timeout' => 10, 
    'emptySet' => true,
    'retryAttempts' => 3
];
````

After configuring options, initiate a Graph connection

````
use TheDonHimself\GremlinOGM\GraphConnection;

....

$graph = (new GraphConnection($options))->init();
$graph_connection = $graph->getConnection();

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

Again these are just proxies to the underlying brightzone/gremlin-php library, so what does gremlin-ogm do? Gremlin-OGM helps you map PHP objects to Graph Vertexes, Edges, Properties and Indexes. It also provides a way to validate that mapping and point out areas where the schema fails. Moreover, the library attempts to create a cross-vendor abstraction layer because different providers might give different ways to execute gremlin commands. 

#### Current Tested & Supported Vendors:

- [ ] Amazon Neptune
- [x] Azure Cosmos DB
- [ ] Datastax Enterprise Graph
- [x] JanusGraph on Compose
- [x] JanusGraph Self-Hosted
- [ ] Neo4j
- [ ] OrientDB

To tell the library of a specific vendor extend the options array as shown for self-hosted

````
$options = [
....
    'vendor' => [ 
      'name' => '_self', 
      'database' => 'janusgraph', 
      'version' => '0.3.1'
    ],
....
];
````

Omiting the version will make the library assume the latest.

An example configuration for JanusGraph on Compose would look like this.

````
$options = [
....
    'vendor' => [ 
      'name' => 'compose', 
      'graph' => 'twitter',
      'database' => 'janusgraph', 
      'version' => '0.3.1'
    ],
....
];
````

An example configuration for CosmosDB on Azure would look like this.

````
$options = [
....
    'vendor' => [ 
      'name' => 'azure', 
      'database' => 'cosmosdb'
    ],
....
];
````

Vendor information is critical to the library so as to know whether to enable certain features like [bindings](tinkerpop.apache.org/docs/current/reference/#parameterized-scripts) which is does by default and may not work in all situations. Submitted vendor information can now be gotten as an array under the graph object
````
$vendor = $graph->getVendor();
````

## Sample Usage

Please Check Out [The-Don-Himself/twitter-graph](https://github.com/The-Don-Himself/twitter-graph), for a full working sample code of how to use this library to map your Twitter connection and tweets onto a compatible graph database.

## GraphQL

You might also be interested in [The-Don-Himself/graphql2gremlin](https://github.com/The-Don-Himself/graphql2gremlin), an attempt to create a standard around transforming GraphQL queries to Gremlin Traversals.
