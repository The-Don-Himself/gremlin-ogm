<?php

namespace TheDonHimself\GremlinOGM\Manager;

class GraphManager
{
    /**
     * Gets the repository for a graph class.
     *
     * @param GraphManager $graphManager The GraphManager instance.
     * @param string          $label    The label of the graph element.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository(GraphManager $graphManager, $documentName);
}
