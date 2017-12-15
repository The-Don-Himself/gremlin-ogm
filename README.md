# gremlin-ogm
A PHP Object Graph Mapper for Tinkerpop 3+ compatible Graph Databases (JanusGraph, Neo4j, etc.) that allows you to persist data and run gremlin queries.

Check out the TwitterGraph folder for an elaborate example of how to use gremlin-ogm to graph Twitter in a Graph Database.


##### Note: Still a work in progress. I'm progressively open sourcing the code I use in production with JanusGraph. Currently, only a small percentage is my own stuff while the rest using code from the dev-master branch. Once I can run 100% off this library, I will release the first stable version 0.0.1. If you use Symfony, then there's a bundle I'm working on that will allow you to map Doctrine ORM entities from a compatible RDBMS database as well as Doctrine ODM documents from MongoDB to a Graph Database.


## Usage
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
$graph = (new \TheDonHimself\GremlinOGM\GraphConnection($options))->init();
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

#### Current Tested Vendors:

- [x] Azure Cosmos DB
- [ ] Datastax Enterprise Graph
- [ ] IBM Graph
- [x] JanusGraph on Compose
- [x] JanusGraph Self-Hosted
- [ ] Neo4j
- [ ] OrientDB

To tell the of a specific vendor extend the options array as shown for self-hosted

````
$options = [
....
    'vendor' = [ 
      'name' => '_self', 
      'database' => 'janusgraph', 
      'version' => '0.2'
    ],
....
];
````

Omiting the version will make the library assume the latest.

An example configuration for JanusGraph on Compose would look like this.

````
$options = [
....
    'vendor' = [ 
      'name' => 'compose', 
      'graph' => 'twitter',
      'database' => 'janusgraph', 
      'version' => '0.1.1'
    ],
....
];
````

An example configuration for CosmosDB on Azure would look like this.

````
$options = [
....
    'vendor' = [ 
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


On to the fun now.

#### Create A Schema

First we deal with vertexes (a few places I called them vertices, but I'll update everywhere to be vertexes and indexes)

##### Vertexes

````
<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices;

use JMS\Serializer\Annotation as Serializer;
use TheDonHimself\GremlinOGM\Annotation as Graph;

/**
 *  @Serializer\ExclusionPolicy("all")
 *  @Graph\Vertex(
 *      label="tweets",
 *      indexes={
 *          @Graph\Index(
 *              name="byTweetsIdComposite",
 *              type="Composite",
 *              unique=true,
 *              label_constraint=true,
 *              keys={
 *                  "tweets_id"
 *              }
 *          ),
 *          @Graph\Index(
 *              name="tweetsMixed",
 *              type="Mixed",
 *              label_constraint=true,
 *              keys={
 *                  "tweets_id"       : "DEFAULT",
 *                  "text"            : "TEXT",
 *                  "retweet_count"   : "DEFAULT",
 *                  "created_at"      : "DEFAULT",
 *                  "favorited"       : "DEFAULT",
 *                  "retweeted"       : "DEFAULT",
 *                  "source"          : "STRING"
 *              }
 *          )
 *      }
 *  )
 */
class Tweets
{
    /**
     * @Serializer\Type("integer")
     * @Serializer\Expose
     * @Serializer\Groups({"Default"})
     */
    public $id;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\Expose
     * @Serializer\Type("integer")
     * @Serializer\Groups({"Graph"})
     * @Serializer\SerializedName("tweets_id")
     * @Graph\Id
     * @Graph\PropertyName("tweets_id")
     * @Graph\PropertyType("Long")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public function getVirtualId()
    {
        return self::getId();
    }

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("text")
     * @Graph\PropertyType("String")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $text;

    /**
     * @Serializer\Type("integer")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("retweet_count")
     * @Graph\PropertyType("Integer")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $retweet_count;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("favorited")
     * @Graph\PropertyType("Boolean")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $favorited;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("retweeted")
     * @Graph\PropertyType("Boolean")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $retweeted;

    /**
     * @Serializer\Type("DateTime<'', '', 'D M d H:i:s P Y'>")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("created_at")
     * @Graph\PropertyType("Date")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $created_at;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("source")
     * @Graph\PropertyType("String")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $source;

    /**
     * @Serializer\Type("TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices\Users")
     * @Serializer\Expose
     * @Serializer\Groups({"Default"})
     */
    public $user;

    /**
     * @Serializer\Type("TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices\Tweets")
     * @Serializer\Expose
     * @Serializer\Groups({"Default"})
     */
    public $retweeted_status;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
````

##### Edges

And an edge

````
<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges;

use JMS\Serializer\Annotation as Serializer;
use TheDonHimself\GremlinOGM\Annotation as Graph;

/**
 *  @Serializer\ExclusionPolicy("all")
 *  @Graph\Edge(
 *      label="follows",
 *      multiplicity="MULTI"
 *  )
 */
class Follows
{
    /**
     *  @Graph\AddEdgeFromVertex(
     *      targetVertex="users",
     *      uniquePropertyKey="users_id",
     *      methodsForKeyValue={"getUserVertex1Id"}
     *  )
     */
    protected $userVertex1Id;

    /**
     *  @Graph\AddEdgeToVertex(
     *      targetVertex="users",
     *      uniquePropertyKey="users_id",
     *      methodsForKeyValue={"getUserVertex2Id"}
     *  )
     */
    protected $userVertex2Id;

    public function __construct($user1_vertex_id, $user2_vertex_id)
    {
        $this->userVertex1Id = $user1_vertex_id;
        $this->userVertex2Id = $user2_vertex_id;
    }

    /**
     * Get User 1 Vertex ID.
     *
     *
     * @return int
     */
    public function getUserVertex1Id()
    {
        return $this->userVertex1Id;
    }

    /**
     * Get User 2 Vertex ID.
     *
     *
     * @return int
     */
    public function getUserVertex2Id()
    {
        return $this->userVertex2Id;
    }
}
````

The beauty of this library is that it only helps you write gremlin commands but does not stop you from interfacing with Gremlin directly, for example as in the case of the Follows Edge above, the library will produce gremlin commands to create an edge between two vertexes if you can pass to it a unique identifier such as user_id, house_id, taxi_id, etc. If you want to added an edge by other ways you can simply write a gremlin command and submit it directly.

The follows edges class is really simple, in that it simply creates an edge linking two vertexes by user_id, in real life examples you'd probably create the edge but have added properties like followed_on, via_app, introduced_by and so on. Just add those properties to the class and let the library serialize them for you.


##### Create Schema

When creating vertex and edge classes, look at the code from \TheDonHimself\GremlinOGM\TwitterGraph\Commands,
They include;

SchemaCheckCommand;
SchemaCreateCommand;
PopulateCommand;
VertexesCountCommand;
VertexesDropCommand;
EdgesCountCommand;
EdgesDropCommand;
GremlinTraversalCommand;

SchemaCheckCommand runs some checks to ensure that you did not duplicate names of properties and labels or indexes while SchemaCreateCommand actually iterates through you graph classes and send gremlin commands to create them. PopulateCommand populates the graph with data either from an API as with the case of the sample TwitterGraph or from a databases if you use Doctrine ORM (RDBMS) and/or Doctrine ODM (MongoDB). GremlinTraversalCommand let you send a gremlin command through the CLI e.g php bin/graph twittergraph:gremlin:traversal --traversal="g.V().count()".

##### Traverse The Graph

The library is almost a seamless transition from the Gremlin API. The most important thing here is the TraversalBuilder from \TheDonHimself\Traversal\TraversalBuilder which returns ready to execute gremlin commands, for example to get back a users vertex from Twitter you can build a Traversal as follows

````
$user_id = 12345;

$traversalBuilder = new TraversalBuilder();

$command = $traversalBuilder
  ->g()
  ->V()
  ->hasLabel("'users'")
  ->has("'users_id'", "$user_id")
  ->getTraversal();

return $command;
````

Take special note of the single and double quotes

Echoing this command will show you this
````
g.V().hasLabel('users').has('users_id', 12345)
````

Re: check possible traversal steps in at the code from \TheDonHimself\Traversal\Step,


Let' get a little bit more complex now, fetching a user's feed

````
$screen_name = 'my_username';

$traversalBuilder = new TraversalBuilder();

$command = $traversalBuilder
  ->g()
  ->V()
  ->hasLabel("'users'")
  ->has("'screen_name'", "'$screen_name'")
  ->union(
    (new TraversalBuilder())->out("'tweeted'")->getTraversal(),
    (new TraversalBuilder())->out("'follows'")->out("'tweeted'")->getTraversal()
  )
  ->order()
  ->by("'created_at'", 'decr')
  ->limit(10)
  ->getTraversal();

return $command;
````

Echoing this command will show you this
````
g.V().hasLabel('users').has('screen_name', 'my_username').union(out('tweeted'), out('follows').out('tweeted')).order().by('created_at', decr).limit(10)
````

That's it for now, there is so much more that this simple library can do, please look in the sample TwitterGraph folder of quickly get started with a sample graph of your Twitter friends, followers, likes, tweets and retweets by running this command. The library comes with a preconfigured readonly Twitter App for this.

php bin/graph twittergraph:gremlin:traversal


## Tests

Currently, I've not written any test suites but you can test the library by using a sample Twitter Graph that comes preconfigured with this library. Only the following Graph Databases have been tested to work though will test more when I get the time/resources

- [x] Azure Cosmos DB
- [x] JanusGraph on Compose
- [x] JanusGraph Self-Hosted

Simple configure any of them in their respective json files in the root folder then execute the following

**Azure Cosmos DB**

````
php bin/graph twittergraph:populate twitter_handle --configPath="\path\to\gremlin-ogm\azure-cosmosdb.json"
````

example:

C:\wamp64\www\gremlin-ogm>php bin/graph twittergraph:populate The_Don_Himself --configPath="\wamp64\www\gremlin-ogm\azure-cosmosdb.json"
Twitter User @The_Don_Himself Found
Twitter ID : 622225192
Creating Vertexes...
Done! 338 Vertexes Created
Creating Edges...
Done! 367 Edges Created
Graph Populated Successfully!

C:\wamp64\www\gremlin-ogm>


**JanusGraph on Compose**

````
php bin/graph twittergraph:populate twitter_handle --configPath="\path\to\gremlin-ogm\janusgraph-compose.json"
````

example:

C:\wamp64\www\gremlin-ogm>php bin/graph twittergraph:populate The_Don_Himself --configPath="\wamp64\www\gremlin-ogm\janusgraph-compose.json"
Twitter User @The_Don_Himself Found
Twitter ID : 622225192
Creating Vertexes...
Done! 338 Vertexes Created
Creating Edges...
Done! 367 Edges Created
Graph Populated Successfully!

C:\wamp64\www\gremlin-ogm>


**JanusGraph Self-Hosted**

````
php bin/graph twittergraph:populate twitter_handle --configPath="\path\to\gremlin-ogm\janusgraph.json"
````

example:

C:\wamp64\www\gremlin-ogm>php bin/graph twittergraph:populate The_Don_Himself --configPath="\wamp64\www\gremlin-ogm\janusgraph.json"
Twitter User @The_Don_Himself Found
Twitter ID : 622225192
Creating Vertexes...
Done! 338 Vertexes Created
Creating Edges...
Done! 367 Edges Created
Graph Populated Successfully!

C:\wamp64\www\gremlin-ogm>
