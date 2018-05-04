<?php

namespace TheDonHimself\GremlinOGM\Traversal;

use TheDonHimself\GremlinOGM\Traversal\Step\AddEdgeStep;
use TheDonHimself\GremlinOGM\Traversal\Step\AddEStep;
use TheDonHimself\GremlinOGM\Traversal\Step\AddPropertyStep;
use TheDonHimself\GremlinOGM\Traversal\Step\AddVertexStep;
use TheDonHimself\GremlinOGM\Traversal\Step\AddVStep;
use TheDonHimself\GremlinOGM\Traversal\Step\AggregateStep;
use TheDonHimself\GremlinOGM\Traversal\Step\AndStep;
use TheDonHimself\GremlinOGM\Traversal\Step\AsStep;
use TheDonHimself\GremlinOGM\Traversal\Step\BarrierStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ByStep;
use TheDonHimself\GremlinOGM\Traversal\Step\CapStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ChooseStep;
use TheDonHimself\GremlinOGM\Traversal\Step\CoalesceStep;
use TheDonHimself\GremlinOGM\Traversal\Step\CoinStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ConstantStep;
use TheDonHimself\GremlinOGM\Traversal\Step\CountStep;
use TheDonHimself\GremlinOGM\Traversal\Step\CyclicPathStep;
use TheDonHimself\GremlinOGM\Traversal\Step\DedupStep;
use TheDonHimself\GremlinOGM\Traversal\Step\DropStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ExplainStep;
use TheDonHimself\GremlinOGM\Traversal\Step\FoldStep;
use TheDonHimself\GremlinOGM\Traversal\Step\FromStep;
use TheDonHimself\GremlinOGM\Traversal\Step\GraphStep\EStep;
use TheDonHimself\GremlinOGM\Traversal\Step\GraphStep\VStep;
use TheDonHimself\GremlinOGM\Traversal\Step\GroupCountStep;
use TheDonHimself\GremlinOGM\Traversal\Step\GroupStep;
use TheDonHimself\GremlinOGM\Traversal\Step\HasIdStep;
use TheDonHimself\GremlinOGM\Traversal\Step\HasKeyStep;
use TheDonHimself\GremlinOGM\Traversal\Step\HasLabelStep;
use TheDonHimself\GremlinOGM\Traversal\Step\HasNotStep;
use TheDonHimself\GremlinOGM\Traversal\Step\HasStep;
use TheDonHimself\GremlinOGM\Traversal\Step\HasValueStep;
use TheDonHimself\GremlinOGM\Traversal\Step\IdStep;
use TheDonHimself\GremlinOGM\Traversal\Step\InjectStep;
use TheDonHimself\GremlinOGM\Traversal\Step\IsStep;
use TheDonHimself\GremlinOGM\Traversal\Step\KeyStep;
use TheDonHimself\GremlinOGM\Traversal\Step\LabelStep;
use TheDonHimself\GremlinOGM\Traversal\Step\LimitStep;
use TheDonHimself\GremlinOGM\Traversal\Step\LocalStep;
use TheDonHimself\GremlinOGM\Traversal\Step\LoopsStep;
use TheDonHimself\GremlinOGM\Traversal\Step\MatchStep;
use TheDonHimself\GremlinOGM\Traversal\Step\MaxStep;
use TheDonHimself\GremlinOGM\Traversal\Step\MeanStep;
use TheDonHimself\GremlinOGM\Traversal\Step\MinStep;
use TheDonHimself\GremlinOGM\Traversal\Step\NotStep;
use TheDonHimself\GremlinOGM\Traversal\Step\OptionalStep;
use TheDonHimself\GremlinOGM\Traversal\Step\OptionStep;
use TheDonHimself\GremlinOGM\Traversal\Step\OrderStep;
use TheDonHimself\GremlinOGM\Traversal\Step\OrStep;
use TheDonHimself\GremlinOGM\Traversal\Step\PageRankStep;
use TheDonHimself\GremlinOGM\Traversal\Step\PathStep;
use TheDonHimself\GremlinOGM\Traversal\Step\PeerPressureStep;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\BetweenPredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\EqPredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\GtePredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\GtPredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\InsidePredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\LtePredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\LtPredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\NeqPredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\OutsidePredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\WithinPredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\Predicates\WithoutPredicate;
use TheDonHimself\GremlinOGM\Traversal\Step\ProfileStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ProgramStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ProjectStep;
use TheDonHimself\GremlinOGM\Traversal\Step\PropertiesStep;
use TheDonHimself\GremlinOGM\Traversal\Step\PropertyMapStep;
use TheDonHimself\GremlinOGM\Traversal\Step\PropertyStep;
use TheDonHimself\GremlinOGM\Traversal\Step\RangeStep;
use TheDonHimself\GremlinOGM\Traversal\Step\RepeatStep;
use TheDonHimself\GremlinOGM\Traversal\Step\SackStep;
use TheDonHimself\GremlinOGM\Traversal\Step\SampleStep;
use TheDonHimself\GremlinOGM\Traversal\Step\SelectStep;
use TheDonHimself\GremlinOGM\Traversal\Step\SimplePathStep;
use TheDonHimself\GremlinOGM\Traversal\Step\SkipStep;
use TheDonHimself\GremlinOGM\Traversal\Step\StoreStep;
use TheDonHimself\GremlinOGM\Traversal\Step\SubgraphStep;
use TheDonHimself\GremlinOGM\Traversal\Step\SumStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TailStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep\FillStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep\HasNextStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep\NextStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep\ToBulkSetStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep\ToListStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep\ToSetStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep\TryNextStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TimeLimitStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TimesStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ToStep;
use TheDonHimself\GremlinOGM\Traversal\Step\TreeStep;
use TheDonHimself\GremlinOGM\Traversal\Step\UnfoldStep;
use TheDonHimself\GremlinOGM\Traversal\Step\UnionStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ValueMapStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ValuesStep;
use TheDonHimself\GremlinOGM\Traversal\Step\ValueStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\BothEStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\BothStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\BothVStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\InEStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\InStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\InVStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\OtherVStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\OutEStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\OutStep;
use TheDonHimself\GremlinOGM\Traversal\Step\VertexStep\OutVStep;
use TheDonHimself\GremlinOGM\Traversal\Step\WhereStep;

class TraversalBuilder
{
    /**
     * @var string
     */
    private $traversal = '';

    /**
     * Add a raw string to the traversal to handle unaccounted or complex scenarios.
     *
     * @param string $string
     *
     * @return Traversal
     */
    public function raw(string $string)
    {
        $traversal = $this->traversal;

        $new_traversal = $traversal.$string;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * Typically the start of a traversal.
     *
     * @return Traversal
     */
    public function g()
    {
        $traversal = $this->traversal;

        $new_traversal = $traversal.'g';

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function addE(...$args)
    {
        $traversal = $this->traversal;

        $addE = new AddEStep($args);
        $addE_traversal = $addE->__toString();

        $new_traversal = $traversal.$addE_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function addEdge(...$args)
    {
        $traversal = $this->traversal;

        $addEdge = new AddEdgeStep($args);
        $addEdge_traversal = $addEdge->__toString();

        $new_traversal = $traversal.$addEdge_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function addProperty(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $addProperty_traversal = '.addProperty('.$inner_traversal.')';

            $new_traversal = $traversal.$addProperty_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $addProperty = new AddPropertyStep($args);
        $addProperty_traversal = $addProperty->__toString();

        $new_traversal = $traversal.$addProperty_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function property(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $property_traversal = '.property('.$inner_traversal.')';

            $new_traversal = $traversal.$property_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $property = new PropertyStep($args);
        $property_traversal = $property->__toString();

        $new_traversal = $traversal.$property_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function addV(...$args)
    {
        $traversal = $this->traversal;

        $addV = new AddVStep($args);
        $addV_traversal = $addV->__toString();

        $new_traversal = $traversal.$addV_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function addVertex(...$args)
    {
        $traversal = $this->traversal;

        $addVertex = new AddVertexStep($args);
        $addVertex_traversal = $addVertex->__toString();

        $new_traversal = $traversal.$addVertex_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function aggregate(...$args)
    {
        $traversal = $this->traversal;

        $aggregate = new AggregateStep($args);
        $aggregate_traversal = $aggregate->__toString();

        $new_traversal = $traversal.$aggregate_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function and(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $and_traversal = '.and('.$inner_traversal.')';

            $new_traversal = $traversal.$and_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $and = new AndStep($args);
        $and_traversal = $and->__toString();

        $new_traversal = $traversal.$and_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function as(...$args)
    {
        $traversal = $this->traversal;

        $as = new AsStep($args);
        $as_traversal = $as->__toString();

        $new_traversal = $traversal.$as_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function barrier(...$args)
    {
        $traversal = $this->traversal;

        $barrier = new BarrierStep($args);
        $barrier_traversal = $barrier->__toString();

        $new_traversal = $traversal.$barrier_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function by(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $by_traversal = '.by('.$inner_traversal.')';

            $new_traversal = $traversal.$by_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $by = new ByStep($args);
        $by_traversal = $by->__toString();

        $new_traversal = $traversal.$by_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function cap(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $cap_traversal = '.cap('.$inner_traversal.')';

            $new_traversal = $traversal.$cap_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $cap = new CapStep($args);
        $cap_traversal = $cap->__toString();

        $new_traversal = $traversal.$cap_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function choose(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $choose_traversal = '.choose('.$inner_traversal.')';

            $new_traversal = $traversal.$choose_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $choose = new ChooseStep($args);
        $choose_traversal = $choose->__toString();

        $new_traversal = $traversal.$choose_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function coalesce(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $coalesce_traversal = '.coalesce('.$inner_traversal.')';

            $new_traversal = $traversal.$coalesce_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $coalesce = new CoalesceStep($args);
        $coalesce_traversal = $coalesce->__toString();

        $new_traversal = $traversal.$coalesce_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function coin(...$args)
    {
        $traversal = $this->traversal;

        $coin = new CoinStep($args);
        $coin_traversal = $coin->__toString();

        $new_traversal = $traversal.$coin_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function constant(...$args)
    {
        $traversal = $this->traversal;

        $constant = new ConstantStep($args);
        $constant_traversal = $constant->__toString();

        $new_traversal = $traversal.$constant_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function count(...$args)
    {
        $traversal = $this->traversal;

        $count = new CountStep($args);
        $count_traversal = $count->__toString();

        $new_traversal = $traversal.$count_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function cyclicPath(...$args)
    {
        $traversal = $this->traversal;

        $cyclicPath = new CyclicPathStep($args);
        $cyclicPath_traversal = $cyclicPath->__toString();

        $new_traversal = $traversal.$cyclicPath_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function dedup(...$args)
    {
        $traversal = $this->traversal;

        $dedup = new DedupStep($args);
        $dedup_traversal = $dedup->__toString();

        $new_traversal = $traversal.$dedup_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function drop(...$args)
    {
        $traversal = $this->traversal;

        $drop = new DropStep($args);
        $drop_traversal = $drop->__toString();

        $new_traversal = $traversal.$drop_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function explain(...$args)
    {
        $traversal = $this->traversal;

        $explain = new ExplainStep($args);
        $explain_traversal = $explain->__toString();

        $new_traversal = $traversal.$explain_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function fold(...$args)
    {
        $traversal = $this->traversal;

        $fold = new FoldStep($args);
        $fold_traversal = $fold->__toString();

        $new_traversal = $traversal.$fold_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function V(...$args)
    {
        $traversal = $this->traversal;

        $graph = new VStep($args);
        $graph_traversal = $graph->__toString();

        $new_traversal = $traversal.$graph_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function E(...$args)
    {
        $traversal = $this->traversal;

        $graph = new EStep($args);
        $graph_traversal = $graph->__toString();

        $new_traversal = $traversal.$graph_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function from(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $from_traversal = '.from('.$inner_traversal.')';

            $new_traversal = $traversal.$from_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $from = new FromStep($args);
        $from_traversal = $from->__toString();

        $new_traversal = $traversal.$from_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function group(...$args)
    {
        $traversal = $this->traversal;

        $group = new GroupStep($args);
        $group_traversal = $group->__toString();

        $new_traversal = $traversal.$group_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function groupCount(...$args)
    {
        $traversal = $this->traversal;

        $groupCount = new GroupCountStep($args);
        $groupCount_traversal = $groupCount->__toString();

        $new_traversal = $traversal.$groupCount_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function has(...$args)
    {
        $traversal = $this->traversal;

        $has = new HasStep($args);
        $has_traversal = $has->__toString();

        $new_traversal = $traversal.$has_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function hasLabel(...$args)
    {
        $traversal = $this->traversal;

        $hasLabel = new HasLabelStep($args);
        $hasLabel_traversal = $hasLabel->__toString();

        $new_traversal = $traversal.$hasLabel_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function hasId(...$args)
    {
        $traversal = $this->traversal;

        $hasId = new HasIdStep($args);
        $hasId_traversal = $hasId->__toString();

        $new_traversal = $traversal.$hasId_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function hasKey(...$args)
    {
        $traversal = $this->traversal;

        $hasKey = new HasKeyStep($args);
        $hasKey_traversal = $hasKey->__toString();

        $new_traversal = $traversal.$hasKey_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function hasValue(...$args)
    {
        $traversal = $this->traversal;

        $hasValue = new HasValueStep($args);
        $hasValue_traversal = $hasValue->__toString();

        $new_traversal = $traversal.$hasValue_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function hasNot(...$args)
    {
        $traversal = $this->traversal;

        $hasNot = new HasNotStep($args);
        $hasNot_traversal = $hasNot->__toString();

        $new_traversal = $traversal.$hasNot_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function id(...$args)
    {
        $traversal = $this->traversal;

        $id = new IdStep($args);
        $id_traversal = $id->__toString();

        $new_traversal = $traversal.$id_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function inject(...$args)
    {
        $traversal = $this->traversal;

        $inject = new InjectStep($args);
        $inject_traversal = $inject->__toString();

        $new_traversal = $traversal.$inject_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function is(...$args)
    {
        $traversal = $this->traversal;

        $is = new IsStep($args);
        $is_traversal = $is->__toString();

        $new_traversal = $traversal.$is_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function label(...$args)
    {
        $traversal = $this->traversal;

        $label = new LabelStep($args);
        $label_traversal = $label->__toString();

        $new_traversal = $traversal.$label_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function key(...$args)
    {
        $traversal = $this->traversal;

        $key = new KeyStep($args);
        $key_traversal = $key->__toString();

        $new_traversal = $traversal.$key_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function limit(...$args)
    {
        $traversal = $this->traversal;

        $limit = new LimitStep($args);
        $limit_traversal = $limit->__toString();

        $new_traversal = $traversal.$limit_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function local(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $local_traversal = '.local('.$inner_traversal.')';

            $new_traversal = $traversal.$local_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $local = new LocalStep($args);
        $local_traversal = $local->__toString();

        $new_traversal = $traversal.$local_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function loops(...$args)
    {
        $traversal = $this->traversal;

        $loops = new LoopsStep($args);
        $loops_traversal = $loops->__toString();

        $new_traversal = $traversal.$loops_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function match(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $match_traversal = '.match('.$inner_traversal.')';

            $new_traversal = $traversal.$match_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $match = new MatchStep($args);
        $match_traversal = $match->__toString();

        $new_traversal = $traversal.$match_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function max(...$args)
    {
        $traversal = $this->traversal;

        $max = new MaxStep($args);
        $max_traversal = $max->__toString();

        $new_traversal = $traversal.$max_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function mean(...$args)
    {
        $traversal = $this->traversal;

        $mean = new MeanStep($args);
        $mean_traversal = $mean->__toString();

        $new_traversal = $traversal.$mean_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function min(...$args)
    {
        $traversal = $this->traversal;

        $min = new MinStep($args);
        $min_traversal = $min->__toString();

        $new_traversal = $traversal.$min_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function not(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $not_traversal = '.not('.$inner_traversal.')';

            $new_traversal = $traversal.$not_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $not = new NotStep($args);
        $not_traversal = $not->__toString();

        $new_traversal = $traversal.$not_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function option(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $option_traversal = '.option('.$inner_traversal.')';

            $new_traversal = $traversal.$option_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $option = new OptionStep($args);
        $option_traversal = $option->__toString();

        $new_traversal = $traversal.$option_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function optional(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $optional_traversal = '.optional('.$inner_traversal.')';

            $new_traversal = $traversal.$optional_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $optional = new OptionalStep($args);
        $optional_traversal = $optional->__toString();

        $new_traversal = $traversal.$optional_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function or(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $or_traversal = '.or('.$inner_traversal.')';

            $new_traversal = $traversal.$or_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $or = new OrStep($args);
        $or_traversal = $or->__toString();

        $new_traversal = $traversal.$or_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function order(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $order_traversal = '.order('.$inner_traversal.')';

            $new_traversal = $traversal.$order_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $order = new OrderStep($args);
        $order_traversal = $order->__toString();

        $new_traversal = $traversal.$order_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function pageRank(...$args)
    {
        $traversal = $this->traversal;

        $pageRank = new PageRankStep($args);
        $pageRank_traversal = $pageRank->__toString();

        $new_traversal = $traversal.$pageRank_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function path(...$args)
    {
        $traversal = $this->traversal;

        $path = new PathStep($args);
        $path_traversal = $path->__toString();

        $new_traversal = $traversal.$path_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function peerPressure(...$args)
    {
        $traversal = $this->traversal;

        $peerPressure = new PeerPressureStep($args);
        $peerPressure_traversal = $peerPressure->__toString();

        $new_traversal = $traversal.$peerPressure_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function profile(...$args)
    {
        $traversal = $this->traversal;

        $profile = new ProfileStep($args);
        $profile_traversal = $profile->__toString();

        $new_traversal = $traversal.$profile_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function project(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $project_traversal = '.project('.$inner_traversal.')';

            $new_traversal = $traversal.$project_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $project = new ProjectStep($args);
        $project_traversal = $project->__toString();

        $new_traversal = $traversal.$project_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function program(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $program_traversal = '.program('.$inner_traversal.')';

            $new_traversal = $traversal.$program_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $program = new ProgramStep($args);
        $program_traversal = $program->__toString();

        $new_traversal = $traversal.$program_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function properties(...$args)
    {
        $traversal = $this->traversal;

        $properties = new PropertiesStep($args);
        $properties_traversal = $properties->__toString();

        $new_traversal = $traversal.$properties_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function propertyMap(...$args)
    {
        $traversal = $this->traversal;

        $propertyMap = new PropertyMapStep($args);
        $propertyMap_traversal = $propertyMap->__toString();

        $new_traversal = $traversal.$propertyMap_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function range(...$args)
    {
        $traversal = $this->traversal;

        $range = new RangeStep($args);
        $range_traversal = $range->__toString();

        $new_traversal = $traversal.$range_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function repeat(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $repeat_traversal = '.repeat('.$inner_traversal.')';

            $new_traversal = $traversal.$repeat_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $repeat = new RepeatStep($args);
        $repeat_traversal = $repeat->__toString();

        $new_traversal = $traversal.$repeat_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function sack(...$args)
    {
        $traversal = $this->traversal;

        $sack = new SackStep($args);
        $sack_traversal = $sack->__toString();

        $new_traversal = $traversal.$sack_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function sample(...$args)
    {
        $traversal = $this->traversal;

        $sample = new SampleStep($args);
        $sample_traversal = $sample->__toString();

        $new_traversal = $traversal.$sample_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function select(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $select_traversal = '.select('.$inner_traversal.')';

            $new_traversal = $traversal.$select_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $select = new SelectStep($args);
        $select_traversal = $select->__toString();

        $new_traversal = $traversal.$select_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function simplePath(...$args)
    {
        $traversal = $this->traversal;

        $simplePath = new SimplePathStep($args);
        $simplePath_traversal = $simplePath->__toString();

        $new_traversal = $traversal.$simplePath_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function skip(...$args)
    {
        $traversal = $this->traversal;

        $skip = new SkipStep($args);
        $skip_traversal = $skip->__toString();

        $new_traversal = $traversal.$skip_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function store(...$args)
    {
        $traversal = $this->traversal;

        $store = new StoreStep($args);
        $store_traversal = $store->__toString();

        $new_traversal = $traversal.$store_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function subgraph(...$args)
    {
        $traversal = $this->traversal;

        $subgraph = new SubgraphStep($args);
        $subgraph_traversal = $subgraph->__toString();

        $new_traversal = $traversal.$subgraph_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function sum(...$args)
    {
        $traversal = $this->traversal;

        $sum = new SumStep($args);
        $sum_traversal = $sum->__toString();

        $new_traversal = $traversal.$sum_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function tail(...$args)
    {
        $traversal = $this->traversal;

        $tail = new TailStep($args);
        $tail_traversal = $tail->__toString();

        $new_traversal = $traversal.$tail_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function timeLimit(...$args)
    {
        $traversal = $this->traversal;

        $timeLimit = new TimeLimitStep($args);
        $timeLimit_traversal = $timeLimit->__toString();

        $new_traversal = $traversal.$timeLimit_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function times(...$args)
    {
        $traversal = $this->traversal;

        $times = new TimesStep($args);
        $times_traversal = $times->__toString();

        $new_traversal = $traversal.$times_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function to(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $to_traversal = '.to('.$inner_traversal.')';

            $new_traversal = $traversal.$to_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $to = new ToStep($args);
        $to_traversal = $to->__toString();

        $new_traversal = $traversal.$to_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function tree(...$args)
    {
        $traversal = $this->traversal;

        $tree = new TreeStep($args);
        $tree_traversal = $tree->__toString();

        $new_traversal = $traversal.$tree_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function unfold(...$args)
    {
        $traversal = $this->traversal;

        $unfold = new UnfoldStep($args);
        $unfold_traversal = $unfold->__toString();

        $new_traversal = $traversal.$unfold_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function union(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $union_traversal = '.union('.$inner_traversal.')';

            $new_traversal = $traversal.$union_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $union = new UnionStep($args);
        $union_traversal = $union->__toString();

        $new_traversal = $traversal.$union_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function value(...$args)
    {
        $traversal = $this->traversal;

        $value = new ValueStep($args);
        $value_traversal = $value->__toString();

        $new_traversal = $traversal.$value_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function valueMap(...$args)
    {
        $traversal = $this->traversal;

        $valueMap = new ValueMapStep($args);
        $valueMap_traversal = $valueMap->__toString();

        $new_traversal = $traversal.$valueMap_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function values(...$args)
    {
        $traversal = $this->traversal;

        $values = new ValuesStep($args);
        $values_traversal = $values->__toString();

        $new_traversal = $traversal.$values_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function where(...$args)
    {
        $traversal = $this->traversal;

        if (isset($args[0]) && $args[0] instanceof self) {
            $inner_traversal = $args[0]->getTraversal();

            $where_traversal = '.where('.$inner_traversal.')';

            $new_traversal = $traversal.$where_traversal;

            $this->traversal = $new_traversal;

            return $this;
        }

        $where = new WhereStep($args);
        $where_traversal = $where->__toString();

        $new_traversal = $traversal.$where_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function out(...$args)
    {
        $traversal = $this->traversal;

        $out = new OutStep($args);
        $out_traversal = $out->__toString();

        $new_traversal = $traversal.$out_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function in(...$args)
    {
        $traversal = $this->traversal;

        $in = new InStep($args);
        $in_traversal = $in->__toString();

        $new_traversal = $traversal.$in_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function both(...$args)
    {
        $traversal = $this->traversal;

        $both = new BothStep($args);
        $both_traversal = $both->__toString();

        $new_traversal = $traversal.$both_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function outE(...$args)
    {
        $traversal = $this->traversal;

        $outE = new OutEStep($args);
        $outE_traversal = $outE->__toString();

        $new_traversal = $traversal.$outE_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function inE(...$args)
    {
        $traversal = $this->traversal;

        $inE = new InEStep($args);
        $inE_traversal = $inE->__toString();

        $new_traversal = $traversal.$inE_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function bothE(...$args)
    {
        $traversal = $this->traversal;

        $bothE = new BothEStep($args);
        $bothE_traversal = $bothE->__toString();

        $new_traversal = $traversal.$bothE_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function outV(...$args)
    {
        $traversal = $this->traversal;

        $outV = new OutVStep($args);
        $outV_traversal = $outV->__toString();

        $new_traversal = $traversal.$outV_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function inV(...$args)
    {
        $traversal = $this->traversal;

        $inV = new InVStep($args);
        $inV_traversal = $inV->__toString();

        $new_traversal = $traversal.$inV_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function bothV(...$args)
    {
        $traversal = $this->traversal;

        $bothV = new BothVStep($args);
        $bothV_traversal = $bothV->__toString();

        $new_traversal = $traversal.$bothV_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function otherV(...$args)
    {
        $traversal = $this->traversal;

        $otherV = new OtherVStep($args);
        $otherV_traversal = $otherV->__toString();

        $new_traversal = $traversal.$otherV_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function between(...$args)
    {
        $traversal = $this->traversal;

        $between = new BetweenPredicate($args);
        $between_traversal = $between->__toString();

        $new_traversal = $traversal.$between_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function eq(...$args)
    {
        $traversal = $this->traversal;

        $eq = new EqPredicate($args);
        $eq_traversal = $eq->__toString();

        $new_traversal = $traversal.$eq_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function gte(...$args)
    {
        $traversal = $this->traversal;

        $gte = new GtePredicate($args);
        $gte_traversal = $gte->__toString();

        $new_traversal = $traversal.$gte_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function gt(...$args)
    {
        $traversal = $this->traversal;

        $gt = new GtPredicate($args);
        $gt_traversal = $gt->__toString();

        $new_traversal = $traversal.$gt_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function inside(...$args)
    {
        $traversal = $this->traversal;

        $inside = new InsidePredicate($args);
        $inside_traversal = $inside->__toString();

        $new_traversal = $traversal.$inside_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function lte(...$args)
    {
        $traversal = $this->traversal;

        $lte = new LtePredicate($args);
        $lte_traversal = $lte->__toString();

        $new_traversal = $traversal.$lte_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function lt(...$args)
    {
        $traversal = $this->traversal;

        $lt = new LtPredicate($args);
        $lt_traversal = $lt->__toString();

        $new_traversal = $traversal.$lt_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function neq(...$args)
    {
        $traversal = $this->traversal;

        $neq = new NeqPredicate($args);
        $neq_traversal = $neq->__toString();

        $new_traversal = $traversal.$neq_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function outside(...$args)
    {
        $traversal = $this->traversal;

        $outside = new OutsidePredicate($args);
        $outside_traversal = $outside->__toString();

        $new_traversal = $traversal.$outside_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function within(...$args)
    {
        $traversal = $this->traversal;

        $within = new WithinPredicate($args);
        $within_traversal = $within->__toString();

        $new_traversal = $traversal.$within_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function without(...$args)
    {
        $traversal = $this->traversal;

        $without = new WithoutPredicate($args);
        $without_traversal = $without->__toString();

        $new_traversal = $traversal.$without_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function fill(...$args)
    {
        $traversal = $this->traversal;

        $fill = new FillStep($args);
        $fill_traversal = $fill->__toString();

        $new_traversal = $traversal.$fill_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function hasNext(...$args)
    {
        $traversal = $this->traversal;

        $hasNext = new HasNextStep($args);
        $hasNext_traversal = $hasNext->__toString();

        $new_traversal = $traversal.$hasNext_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function next(...$args)
    {
        $traversal = $this->traversal;

        $next = new NextStep($args);
        $next_traversal = $next->__toString();

        $new_traversal = $traversal.$next_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function toBulkSet(...$args)
    {
        $traversal = $this->traversal;

        $toBulkSet = new ToBulkSetStep($args);
        $toBulkSet_traversal = $toBulkSet->__toString();

        $new_traversal = $traversal.$toBulkSet_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function toList(...$args)
    {
        $traversal = $this->traversal;

        $toList = new ToListStep($args);
        $toList_traversal = $toList->__toString();

        $new_traversal = $traversal.$toList_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function toSet(...$args)
    {
        $traversal = $this->traversal;

        $toSet = new ToSetStep($args);
        $toSet_traversal = $toSet->__toString();

        $new_traversal = $traversal.$toSet_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function tryNext(...$args)
    {
        $traversal = $this->traversal;

        $tryNext = new TryNextStep($args);
        $tryNext_traversal = $tryNext->__toString();

        $new_traversal = $traversal.$tryNext_traversal;

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return Traversal
     */
    public function __()
    {
        $traversal = $this->traversal;

        $new_traversal = $traversal.'__';

        $this->traversal = $new_traversal;

        return $this;
    }

    /**
     * @return traversal
     */
    public function getTraversal()
    {
        $traversal = $this->traversal;

        $fixed_traversal = ltrim($traversal, '.');

        return $fixed_traversal;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTraversal();
    }
}
