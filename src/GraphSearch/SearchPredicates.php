<?php

namespace TheDonHimself\GremlinOGM\GraphSearch;

use TheDonHimself\GremlinOGM\Exception\InvalidSearchException;
use TheDonHimself\GremlinOGM\Serializer\GraphSerializer;

class SearchPredicates
{
    /*
        http://tinkerpop.apache.org/docs/current/reference/#a-note-on-predicates

        Predicate	              Description
        ---------               -----------
        eq(object)              Is the incoming object equal to the provided object?
        neq(object)             Is the incoming object not equal to the provided object?
        lt(number)              Is the incoming number less than the provided number?
        lte(number)             Is the incoming number less than or equal to the provided number?
        gt(number)              Is the incoming number greater than the provided number?
        gte(number)             Is the incoming number greater than or equal to the provided number?
        inside(number,number)   Is the incoming number greater than the first provided number and less than the second?
        outside(number,number)  Is the incoming number less than the first provided number or greater than the second?
        between(number,number)  Is the incoming number greater than or equal to the first provided number and less than the second?
        within(objects)         Is the incoming object in the array of provided objects?
        without(objects)        Is the incoming object not in the array of the provided objects?

        JanusGraph Text Predicates  : <link>

        Predicate	                  Description
        ---------                   -----------
        textContains(string)        is true if (at least) one word inside the text string matches the query string
        textContainsPrefix(string)  is true if (at least) one word inside the text string begins with the query string
        textContainsFuzzy(string)   is true if (at least) one word inside the text string is similar to the query String (based on Levenshtein edit distance)
        textPrefix(string)          if the string value starts with the given query string
        textRegex(string)           if the string value matches the given regular expression in its entirety
        textFuzzy(string)           if the string value is similar to the given query string (based on Levenshtein edit distance)

        JanusGraph Geo Predicates   : <link>

        Predicate	                  Description
        ---------                   -----------
        geoIntersect(string)        which holds true if the two geometric objects have at least one point in common (opposite of `geoDisjoint`)
        geoWithin(string)           which holds true if one geometric object contains the other.
        geoDisjoint(string)         which holds true if the two geometric objects have no points in common (opposite of `geoIntersect`)
        geoContains(string)         which holds true if one geometric object is contained by the other.

    */

    /**
     * @var string
     */
    private $original;

    /**
     * @var string
     */
    private $trimmed;

    /**
     * @var string
     */
    private $predicate;

    /**
     * @var string
     */
    private $param1;

    /**
     * @var string
     */
    private $param2;

    /**
     * @var string
     */
    private $params;

    private function startsWith(string $haystack, string $needle)
    {
        $length = mb_strlen($needle, 'UTF-8');

        return mb_substr($haystack, 0, $length, 'UTF-8') === $needle;
    }

    private function endsWith(string $haystack, string $needle)
    {
        $length = mb_strlen($needle, 'UTF-8');

        return mb_substr($haystack, -$length, $length, 'UTF-8') === $needle;
    }

    private function extractMiddle(string $string, string $start, string $end)
    {
        $stringLength = mb_strlen($string, 'UTF-8');
        $startLength = mb_strlen($start, 'UTF-8');
        $endLength = mb_strlen($end, 'UTF-8');

        $middleLength = $stringLength - ($startLength + $endLength);

        return mb_substr($string, $startLength, $middleLength, 'UTF-8');
    }

    public function __construct(string $string, $dataType = 'string')
    {
        $this->original = $string;

        $possible_predicate = trim($string);
        $this->trimmed = $possible_predicate;

        $compare_predicates = array('eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'inside', 'outside', 'between', 'within', 'without');
        $text_predicates = array('textContains', 'textContainsPrefix', 'textContainsFuzzy', 'textPrefix', 'textRegex', 'textFuzzy');
        $geo_predicates = array('geoIntersect', 'geoWithin', 'geoDisjoint', 'geoContains');

        $predicates = array_merge($compare_predicates, $text_predicates, $geo_predicates);

        $graph_serializer = new GraphSerializer();

        foreach ($predicates as $predicate) {
            if ($this->startsWith($possible_predicate, $predicate.'(') && $this->endsWith($possible_predicate, ')')) {
                $this->predicate = $predicate;

                $middle = $this->extractMiddle($possible_predicate, $predicate.'(', ')');

                $geo_param = array();
                $geo_params_array = array();
                $geoshape_predicate_type = null;

                $text_param = null;

                if (in_array($predicate, $text_predicates)) {
                    $dataType = '_text';

                    $item = json_encode($middle, JSON_UNESCAPED_SLASHES);
                    $text_param = $graph_serializer->interchangeQuotes($item);
                }

                if (in_array($predicate, $geo_predicates)) {
                    if (!$this->startsWith($middle, 'Geoshape')) {
                        throw new InvalidSearchException($string, 'Geo predicates must have a Geoshape defined');
                    }

                    $dataType = '_geo';

                    // Take note of the period after Geoshape below it's very important
                    $geo_middle = $this->extractMiddle($middle, 'Geoshape.', '');

                    $geoshape_predicates = array('point', 'circle', 'box', 'fromWkt', 'geoshape');

                    foreach ($geoshape_predicates as $geoshape_predicate) {
                        if (!$this->startsWith($geo_middle, $geoshape_predicate.'(') || !$this->endsWith($geo_middle, ')')) {
                            throw new InvalidSearchException($geoshape_predicate, 'Invalid Geoshape provided '.$geoshape_predicate.' possible Geoshapes include : '.implode(', ', $geoshape_predicates).' and are case sensitive');
                        }

                        if ($this->startsWith($geo_middle, $geoshape_predicate.'(') && $this->endsWith($geo_middle, ')')) {
                            $geoshape_predicate_type = $geoshape_predicate;

                            $geoshape_middle = $this->extractMiddle($geo_middle, $geoshape_predicate_type.'(', ')');

                            if (
                                'point' === $geoshape_predicate_type ||
                                'circle' === $geoshape_predicate_type ||
                                'box' === $geoshape_predicate_type
                            ) {
                                $array = explode(', ', $geoshape_middle);
                                foreach ($array as $value) {
                                    $param = (float) $value;
                                    $geo_params_array[] = $param;
                                }
                            }

                            if (
                                'fromWkt' === $geoshape_predicate_type ||
                                'geoshape' === $geoshape_predicate_type
                            ) {
                                $geo_param = (string) $geoshape_middle;
                            }
                        }
                    }
                }

                $params_array = array();

                if ('string' === $dataType) {
                    $array = explode(',', $middle);
                    foreach ($array as $value) {
                        $item = json_encode($value, JSON_UNESCAPED_SLASHES);
                        $param = $graph_serializer->interchangeQuotes($item);
                        $params_array[] = $param;
                    }
                }
                if ('float' === $dataType || 'double' === $dataType) {
                    $array = explode(',', $middle);
                    foreach ($array as $value) {
                        $param = (float) $value;
                        $params_array[] = $param;
                    }
                }
                if ('int' === $dataType || 'integer' === $dataType) {
                    $array = explode(',', $middle);
                    foreach ($array as $value) {
                        $param = (int) $value;
                        $params_array[] = $param;
                    }
                }

                if (
                    'eq' === $predicate ||
                    'neq' === $predicate ||
                    'lt' === $predicate ||
                    'lte' === $predicate ||
                    'gt' === $predicate ||
                    'gte' === $predicate
                ) {
                    $this->params = implode(', ', $params_array);
                } elseif (
                    'inside' === $predicate ||
                    'outside' === $predicate ||
                    'between' === $predicate
                ) {
                    list($param1, $param2) = $params_array;
                    $this->param1 = $param1;
                    $this->param2 = $param2;
                } elseif (in_array($predicate, $geo_predicates)) {
                    if (
                      'point' === $geoshape_predicate_type ||
                      'circle' === $geoshape_predicate_type ||
                      'box' === $geoshape_predicate_type
                    ) {
                        $this->params = '$geoshape_predicate_type('.implode(', ', $geo_params_array).')';
                    }
                    if (
                      'fromWkt' === $geoshape_predicate_type ||
                      'geoshape' === $geoshape_predicate_type
                    ) {
                        $this->params = '$geoshape_predicate_type('.$geo_param.')';
                    }
                } elseif (in_array($predicate, $text_predicates)) {
                    $this->params = $text_param;
                } else {
                    $this->params = '['.implode(', ', $params_array).']';
                }
            }
        }

        $predicate = $this->predicate;

        if (!$predicate) {
            $this->predicate = 'eq';

            $param = $possible_predicate;

            if ('string' === $dataType) {
                $item = json_encode($possible_predicate, JSON_UNESCAPED_SLASHES);
                $param = $graph_serializer->interchangeQuotes($item);
            }
            if ('float' === $dataType || 'double' === $dataType) {
                $param = (float) $possible_predicate;
            }
            if ('int' === $dataType || 'integer' === $dataType) {
                $param = (int) $possible_predicate;
            }
            if ('_text' === $dataType) {
                $this->predicate = 'textContainsRegex';
                $item = json_encode($possible_predicate, JSON_UNESCAPED_SLASHES);
                $string = $graph_serializer->interchangeQuotes($item);

                $param = "'(?i).*' + $string + '.*'";
            }

            $this->params = $param;
        }
    }

    /**
     * @return predicate
     */
    public function getPredicate()
    {
        $predicate = $this->predicate;

        return $predicate;
    }

    /**
     * @return params
     */
    public function getParams()
    {
        $params = $this->params;

        return $params;
    }

    /**
     * @return param1
     */
    public function getParam1()
    {
        $param1 = $this->param1;

        return $param1;
    }

    /**
     * @return param2
     */
    public function getParam2()
    {
        $param2 = $this->param2;

        return $param2;
    }
}
