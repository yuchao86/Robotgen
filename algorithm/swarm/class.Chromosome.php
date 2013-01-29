<?php

/**
 * class.Chromosome.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/swarm/
 * @version v1.0
 * @license  GPL     
 *
 * @reference
 * 	-Algorithm Reference
 *      -Particle Swarm Optimization
 * @see
 * 	-web Links
 * 	-http://baike.baidu.com/view/367089.htm
 *
 */
/**
 *  Algorithm Description
 * =================================================================
 */

//namespace Robotgen;

if (!class_exists("Chromosome")) {

    // TODO: more docs 
    class Chromosome implements ArrayAccess, Countable, Iterator {

        // TODO: documentation for private fields
        private $genes = array();
        private $fitness = 0;

        public function __construct(array $template = null, $randomize = true) {
            if ($template != null) {
                foreach ($template as $geneName => $genePrototype) {
                    $this->genes[$geneName] = clone $genePrototype;
                    if ($randomize)
                        $this->genes[$geneName]->mutate();
                }
            }
        }

        /** Creates a deep copy of this chromosome.
         * 
         * Usually PHP only creates shallow copies when copying objects.
         * However, we will need a deep copies of Chromosomes, so __clone()
         * implements a deep copy by cloning each gene in the copied
         * chromosome.
         */
        public function __clone() {
            // All genes in $genes need to be cloned if we create a clone of the chromosome
            $copy = $this->genes;
            $this->genes = array();
            foreach ($copy as $key => $value) {
                $this->genes[$key] = clone $value;
            }
        }

        /** Calculates the chromosomes fitness using the given fitness determination strategy.
         * 
         * Calculates the chromosomes fitness and stores it for later retrieval.
         * $strategy determines how the fitness is calculated by implementing
         * the IFitnessDeterminationStrategy interface and providing a method
         * for calculating the fitness.
         * 
         * @param IFitnessDeterminationStrategy $strategy
         */
        public function calculateFitness(IFitnessDeterminationStrategy $strategy) {
            $this->fitness = $strategy->determineFitness($this);
        }

        /** Returns the chromosomes fitness.
         * 
         * Be aware, that this will only return a valid fitness if calculateFitness
         * was called on this chromosome since the last change. It will
         */
        public function getFitness() {
            return $this->fitness;
        }

        /** Recombines this chromosome with another chromosome using the recombination strategy.
         * 
         * Returns a new chromosome which is a descendant of this chromosome
         * and the one given in $partner.
         * 
         * @param Chromosome $partner
         * @param IRecombinationStrategy $strategy
         * @return Chromosome
         */
        public function recombine(Chromosome $partner, IRecombinationStrategy $strategy) {
            return $strategy->recombine($this, $partner);
        }

        //+Countable Interface
        public function count() {
            return count($this->genes);
        }

        //-Countable Interface
        //+Iterator Interface
        public function rewind() {
            reset($this->genes);
        }

        public function current() {
            return current($this->genes);
        }

        public function key() {
            return key($this->genes);
        }

        public function next() {
            return next($this->genes);
        }

        public function valid() {
            return $this->current() !== false;
        }

        //-Iterator Interface
        //+ArrayAccess Interface
        public function offsetExists($offset) {
            return isset($this->genes[$offset]);
        }

        public function offsetGet($offset) {
            return isset($this->genes[$offset]) ? ($this->genes[$offset]) : null;
        }

        public function offsetSet($offset, $value) {
            // Chromosomes should only take objects implementing the IGene interface!
            if (!($value instanceof IGene))
                throw new UnexpectedValueException();
            $this->genes[$offset] = $value;
        }

        public function offsetUnset($offset) {
            unset($this->genes[$offset]);
        }

        //-ArrayAccess Interface
    }

}
?>