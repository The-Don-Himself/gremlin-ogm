<?php

namespace TheDonHimself\GremlinOGM\Traversal;

class BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '(';

    /**
     * @var string
     */
    protected $separator = ', ';

    /**
     * @var string
     */
    protected $postSeparator = ')';

    /**
     * @var array
     */
    protected $parts = array();

    /**
     * @param array $args
     */
    public function __construct($args = array())
    {
        $this->addMultiple($args);
    }

    /**
     * @param array $args
     *
     * @return Base
     */
    public function addMultiple($args = array())
    {
        foreach ((array) $args as $arg) {
            $this->add($arg);
        }

        return $this;
    }

    /**
     * @param mixed $arg
     *
     * @return Base
     */
    public function add($arg)
    {
        if (null !== $arg && (!$arg instanceof self || $arg->count() > 0)) {
            $this->parts[] = $arg;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->parts);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->preSeparator.implode($this->separator, $this->parts).$this->postSeparator;
    }
}
