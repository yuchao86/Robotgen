<?php

/**
 * class.Population.php
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

if (!class_exists("Population")) {

    /** The population class implements a container for any number of chromosomes.
     * 
     * The population class is a container for the Chromosome class. It implements
     * ArrayAccess, Countable and the Iterator interfaces. It provides methods
     * to organize and handle a population of chromomsomes, such as methods
     * to create new generations, split and merge populations and it provides
     * statistical information about the population such as the best and the worst 
     * chromomes, avergage fitness, etc.
     */
    class Population implements ArrayAccess, Countable, Iterator {

        // TODO: documentation for private + protected fields +  methods 
        private $chromosomes = array();
        private $totalFitness = 0;
        private $generation = 0;
        private $bestChromosome = null;
        private $worstChromosome = null;

        protected function selectChromosome(ISelectionStrategy $selectionStrategy) {
            $selectionStrategy->select($this);
        }

        /** Returns the populations average fitness
         * 
         * @return Float
         */
        public function getAverageFitness() {
            return $this->totalFitness / $this->count();
        }

        /** Returns the populations summed up fitness
         * 
         * Used to calculate average fitness.
         * 
         * @return Float
         */
        public function getTotalFitness() {
            return $this->totalFitness;
        }

        /** Returns the most successfull Chromosome
         * 
         * @return Chromosome
         */
        public function getBestChromosome() {
            return $this->bestChromosome;
        }

        /** Returns the least successfull Chromosome
         * 
         * @return Chromosome
         */
        public function getWorstChromosome() {
            return $this->worstChromosome;
        }

        /** Creates the next generation and returns it.
         * 
         * This method creates a new population which is built from
         * children of the current population. Uses the given strategies
         * to create the new population.
         * 
         * Selects two chromosomes using the selection strategy and recombines
         * them using the recombination strategy. The new chromosomes fitness 
         * is determined using the fitness determination strategy. This is
         * repeated until the new populations size equals the old one's.
         * 
         * Internally uses the mergePopulation method.
         * 
         * @param ISelectionStrategy $selectionStrategy
         * @param IRecombinationStrategy $recombinationStrategy
         * @param IFitnessDeterminationStrategy $fitnessDeterminationStrategy
         */
        public function createNextPopulation(ISelectionStrategy $selectionStrategy, IRecombinationStrategy $recombinationStrategy, IFitnessDeterminationStrategy $fitnessDeterminationStrategy) {
            return $this->mergePopulations($this, $selectionStrategy, $recombinationStrategy, $fitnessDeterminationStrategy);
        }

        /** Merges this population with another one and returns the new population.
         * 
         * This method creates a new population which is built from children 
         * of the current population and the one provided as argument. Uses
         * the given strategies to create the new population.
         * 
         * Selects a chromosome from the current population and one from the
         * one provided as argument using the selection strategy, recombines
         * them using the recombination strategy and finally calculates the
         * new chromosomes fitness using the fitness determination strategy.
         * This is repeated until the new populations size equals the old one's.
         * 
         * @param Population $population
         * @param ISelectionStrategy $selectionStrategy
         * @param IRecombinationStrategy $recombinationStrategy
         * @param IFitnessDeterminationStrategy $fitnessDeterminationStrategy
         */
        public function mergePopulations(Population $population, ISelectionStrategy $selectionStrategy, IRecombinationStrategy $recombinationStrategy, IFitnessDeterminationStrategy $fitnessDeterminationStrategy) {
            $nextPopulation = new Population();
            $nextPopulation->generation = $this->generation + 1;

            while ($this->count() > $nextPopulation->count()) {

                $newChromosome = $this->selectChromosome($selectionStrategy)->recombine
                        (
                        $recombinationStrategy, $population->selectChromosome($selectionStrategy)
                );

                $newChromosome->calculateFitnes($fitnessDeterminationStrategy);
                $nextPopulation->totalFitness += $newChromosome->getFitness();

                if ($newChromosome->getFitness() > $bestChromosome->getFitness() || $bestChromosome == null)
                    $bestChromosome = $newChromosome;
                if ($newChromosome->getFitness() < $worstChromosome->getFitness() || $worstChromosome == null)
                    $worstChromosome = $newChromosome;

                array_push($nextPopulation, $newChromosome);
            }

            return $nextPopulation;
        }

        //
        // TODO: add + implement splitPopulation method!

        //
		
		/** Populates the population using a chromosome template.
         * 
         * This method deletes the current chromosomes in the population and
         * creates a new set of chromosomes. It uses an array of IGene as a
         * prototype for the chromosomes. Each chromosome will have a copy of
         * the IGenes provided in $chomosomeTemplate. 
         * 
         * $count specifies the size of the new population.
         * 
         * If $randomize is true the new genes' mutate() method will be
         * called after to randomize the genes' values, else the genes 
         * will simply be copied retaining their original value.
         * 
         * @param array $chromosomeTemplate
         * @param Integer $count
         * @param Boolean $randomize
         */
        public function populate(array $chromosomeTemplate, $count, $randomize = true) {
            unset($this->chromosomes);
            while ($this->count() < $count) {
                $this->chromosomes[] = new Chromosome($chromosomeTemplate, $randomize);
            }
        }

        //+Countable Interface
        public function count() {
            return count($this->chromosomes);
        }

        //-Countable Interface
        //+Iterator Interface
        public function rewind() {
            reset($this->chromosomes);
        }

        public function current() {
            return current($this->chromosomes);
        }

        public function key() {
            return key($this->chromosomes);
        }

        public function next() {
            return next($this->chromosomes);
        }

        public function valid() {
            return $this->current() !== false;
        }

        //-Iterator Interface
        public function end() {
            return end($this->chromosomes);
        }

        //+ArrayAccess Interface
        public function offsetExists($offset) {
            return isset($this->chromosomes[$offset]);
        }

        public function offsetGet($offset) {
            return isset($this->chromosomes[$offset]) ? ($this->chromosomes[$offset]) : null;
        }

        public function offsetSet($offset, $value) {
            // Populations should ony take Chromosome objects!
            if (!($value instanceof Chromosome))
                throw new UnexpectedValueException();
            $this->chromosomes[$offset] = $value;
        }

        public function offsetUnset($offset) {
            unset($this->chromosomes[$offset]);
        }

        //-ArrayAccess Interface
    }

}
?>