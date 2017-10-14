<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Commands;

use Abraham\TwitterOAuth\TwitterOAuth;
use Brightzone\GremlinDriver\InternalException;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TheDonHimself\GremlinOGM\GraphConnection;
use TheDonHimself\GremlinOGM\Serializer\GraphSerializer;
use TheDonHimself\GremlinOGM\Tools\BuildClassMaps;
use TheDonHimself\GremlinOGM\Tools\EdgesPopulate;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges\Follows;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges\Likes;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges\Retweets;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges\Tweeted;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices\Tweets;
use TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices\Users;

class PopulateCommand extends Command
{
    // Array of Vertexes Classes
    private $users_vertexes = array();
    private $tweets_vertexes = array();

    // Array of Edges Classes
    private $retweets_edges = array();
    private $tweeted_edges = array();

    protected function configure()
    {
        $this
            ->setName('twittergraph:populate')
            ->setDescription('TwitterGraph Populate')
            ->addArgument('handle', InputArgument::REQUIRED, 'The Twitter Handle to Populate')
            ->addOption('configPath', null, InputOption::VALUE_OPTIONAL, 'The Path to the JSON Configuration FIle');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $twitter_handle = $input->getArgument('handle');
        $configPath = $input->getOption('configPath');

        $options = array();
        $twitter = array();

        if ($configPath) {
            $configFile = file_get_contents($configPath);
            $config = json_decode($configFile, true);
            $options = $config['options'];
            $twitter = $config['twitter'];
        }

        // Twitter Credentials Defaults
        $consumer_key = 'LnUQzlkWlNT4oNUh7a2rwFtwe';
        $consumer_secret = 'WCIu0YhaOUBPq11lj8psxZYobCjXpYXHxXA6rVcqbuNDYXEoP0';
        $access_token = '622225192-upvfXMpeb9a3FMhuid6oBiCRsiAokpNFgbVeeRxl';
        $access_token_secret = '9M5MnJOns2AFeZbdTeSk3R81ZVjltJCXKtxUav1MgsN7Z';

        if ($twitter) {
            // Twitter Credentials Overrides
            $consumer_key = $twitter['consumer_key'];
            $consumer_secret = $twitter['consumer_secret'];
            $access_token = $twitter['access_token'];
            $access_token_secret = $twitter['access_token_secret'];
        }

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
        $connection->setTimeouts(10, 15);
        $connection->setDecodeJsonAsArray(true);

        // Array of Edges Classes
        $followers_edges = array();
        $friends_edges = array();
        $likes_edges = array();

        // Get Serializer
        $serializer = SerializerBuilder::create()->build();

        // Get Twitter User
        $decoded_user = $connection->get(
            'users/show',
            array(
                'screen_name' => $twitter_handle,
                'include_entities' => false,
            )
        );

        if (404 == $connection->getLastHttpCode()) {
            $output->writeln('Twitter User @'.$twitter_handle.' Does Not Exist');

            return;
        }

        $user = $serializer->fromArray($decoded_user, Users::class);
        $user_id = $user->id;

        $output->writeln('Twitter User @'.$twitter_handle.' Found');
        $output->writeln('Twitter ID : '.$user_id);

        $this->populateUsers($user);

        // Get Followers
        $decoded_followers = $connection->get(
            'followers/list',
            array(
                'screen_name' => $twitter_handle,
                'include_user_entities' => false,
                'count' => 200,
            )
        );

        $followers = $serializer->fromArray($decoded_followers['users'], 'array<'.Users::class.'>');
        foreach ($followers as $follower) {
            $follower_id = $follower->id;
            $followers_edges[$follower_id] = new Follows($follower_id, $user_id);

            $this->populateUsers($follower);
        }

        // Get Friends
        $decoded_friends = $connection->get(
            'friends/list',
            array(
                'screen_name' => $twitter_handle,
                'include_user_entities' => false,
                'count' => 200,
            )
        );

        $friends = $serializer->fromArray($decoded_friends['users'], 'array<'.Users::class.'>');
        foreach ($friends as $friend) {
            $friend_id = $friend->id;
            $friends_edges[$friend_id] = new Follows($user_id, $friend_id);

            $this->populateUsers($friend);
        }

        // Get Likes
        $decoded_likes = $connection->get(
            'favorites/list',
            array(
                'screen_name' => $twitter_handle,
                'include_entities' => false,
                'count' => 200,
            )
        );

        $likes = $serializer->fromArray($decoded_likes, 'array<'.Tweets::class.'>');
        foreach ($likes as $like) {
            $like_id = $like->id;
            $likes_edges[$like_id] = new Likes($user_id, $like_id);

            $this->populateTweet($like);
        }

        // Finished Fetching All The Data From Twitter
        // Now, let's serialize them into gremlin commands

        // First, serialize to array so as to take care of complex conversions
        //  e.g DateTime and GeoPoint, GeoCircle, etc

        $users_vertexes = $this->users_vertexes;
        $tweets_vertexes = $this->tweets_vertexes;

        $graph_serializer = new GraphSerializer();

        $users_array = $graph_serializer->toArray($users_vertexes);
        $tweets_array = $graph_serializer->toArray($tweets_vertexes);

        // Now, let's serialize them into actual gremlin commands

        $vertex_commands = array();

        $label = 'users';
        foreach ($users_array as $user_array) {
            $vertex_array = array(
                $label => $user_array,
            );
            $command = $graph_serializer->toVertex($vertex_array);
            if ($command) {
                $vertex_commands[] = $command;
            }
        }

        $label = 'tweets';
        foreach ($tweets_array as $tweet_array) {
            $vertex_array = array(
                $label => $tweet_array,
            );
            $command = $graph_serializer->toVertex($vertex_array);
            if ($command) {
                $vertex_commands[] = $command;
            }
        }

        // For Edges, things a just a little bit trickier
        // We need to parse the Edge Classes and get metadata from them first

        $twitterGraphPath = dirname(dirname(__FILE__));

        $class_maps = (new BuildClassMaps())->build($twitterGraphPath);

        $edgesPopulateClasses = (new EdgesPopulate())->populate($class_maps);

        // For TwitterGraph, we only need metadat from Edge Class properties
        $graph_populate_edges_properties = $edgesPopulateClasses['properties'];

        $edge_commands = array();

        $retweets_edges = $this->retweets_edges;
        $tweeted_edges = $this->tweeted_edges;

        foreach ($graph_populate_edges_properties as $graph_populate_edges_property) {
            $label = $graph_populate_edges_property['label'];
            $_phpclass = $graph_populate_edges_property['_phpclass'];
            $from_vertex = $graph_populate_edges_property['from'];
            $to_vertex = $graph_populate_edges_property['to'];

            if (Follows::class === $_phpclass) {
                foreach ($friends_edges as $friends_edge) {
                    $command = $graph_serializer->toEdge($label, $from_vertex, $to_vertex, $friends_edge);
                    if ($command) {
                        $edge_commands[] = $command;
                    }
                }
            }
            if (Likes::class === $_phpclass) {
                foreach ($likes_edges as $likes_edge) {
                    $command = $graph_serializer->toEdge($label, $from_vertex, $to_vertex, $likes_edge);
                    if ($command) {
                        $edge_commands[] = $command;
                    }
                }
            }
            if (Retweets::class === $_phpclass) {
                foreach ($retweets_edges as $retweets_edge) {
                    $command = $graph_serializer->toEdge($label, $from_vertex, $to_vertex, $retweets_edge);
                    if ($command) {
                        $edge_commands[] = $command;
                    }
                }
            }
            if (Tweeted::class === $_phpclass) {
                foreach ($tweeted_edges as $tweeted_edge) {
                    $command = $graph_serializer->toEdge($label, $from_vertex, $to_vertex, $tweeted_edge);
                    if ($command) {
                        $edge_commands[] = $command;
                    }
                }
            }
        }

        // Now We have all the vertex and edges commands, let's send them
        // But first we establish a Graph connection

        $graph_connection = (new GraphConnection($options))->init();

        try {
            $graph_connection->open();
        } catch (InternalException $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $output->writeln('Creating Vertexes...');

        $graph_connection->transaction(function (&$graph_connection, $vertex_commands) {
            foreach ($vertex_commands as $command) {
                $graph_connection->send($command);
            }
        }, [&$graph_connection, $vertex_commands]);

        $output->writeln('Creating Edges...');

        $graph_connection->transaction(function (&$graph_connection, $edge_commands) {
            foreach ($edge_commands as $command) {
                $graph_connection->send($command);
            }
        }, [&$graph_connection, $edge_commands]);

        $graph_connection->close();

        $output->writeln('Graph Populated Successfully!');
    }

    private function populateUsers(Users $user)
    {
        $users_vertexes = $this->users_vertexes;

        $user_id = $user->id;
        $users_vertexes[$user_id] = $user;

        $this->users_vertexes = $users_vertexes;

        $status = $user->status;
        if ($status) {
            $tweeted_edges = $this->tweeted_edges;

            $status_id = $status->id;
            $tweeted_edges[$status_id] = new Tweeted($user_id, $status_id);
            $this->tweeted_edges = $tweeted_edges;

            $this->populateTweet($status);
        }
    }

    private function populateTweet(Tweets $tweet)
    {
        $tweets_vertexes = $this->tweets_vertexes;

        $tweet_id = $tweet->id;
        $tweets_vertexes[$tweet_id] = $tweet;
        $this->tweets_vertexes = $tweets_vertexes;

        $user = $tweet->user;
        if ($user) {
            $user_id = $user->id;
            $tweeted_edges = $this->tweeted_edges;

            $tweeted_edges[$tweet_id] = new Tweeted($user_id, $tweet_id);
            $this->tweeted_edges = $tweeted_edges;

            $this->populateUsers($user);
        }

        $retweeted_status = $tweet->retweeted_status;
        if ($retweeted_status) {
            $retweets_edges = $this->retweets_edges;

            $retweeted_id = $retweeted_status->id;
            $retweets_edges[$retweeted_id] = new Retweets($tweet_id, $retweeted_id);
            $this->retweets_edges = $retweets_edges;

            $this->populateTweet($retweeted_status);
        }
    }
}