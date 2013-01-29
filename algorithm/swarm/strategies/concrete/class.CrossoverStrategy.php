<?php

/**
 * class.CrossoverStrategy.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/swarm/strategies/concrete
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

if (!class_exists("CrossoverStrategy")) {

    class CrossoverStrategy implements IRecombinationStrategy {

        public function recombine(Chromosome $male, Chromosome $female) {
            // chromosomes must have same length
            assert($male->count() == $female->count());
            // chromosomes must have at least 2 genes
            assert($male->count() > 1);

            // determine a random position within the male chromosome
            $pos = rand(0, $male->count() - 2);

            // create a chromosome to hold the child
            $child = new Chromosome();

            // prepare the male and female chromosomes
            $male->rewind();
            $female->rewind();
            do {
                // copy the current male gene to the child while preserving the key!
                $child[$male->key()] = clone $male->current();
                // step to the next element
                $male->next();
                $female->next();
            } while ($pos-- > 0); // keep going until we reach the random position
            // from here continue as long as there remain genes in the female chromosome
            while ($female->valid()) {
                // and assign the current female gene to the child while preserving the key
                $child[$female->key()] = clone $female->current();
                // stop to the next element
                $female->next();
            }

            return $child;
        }

        protected function __construct() {
            
        }

        private static $instance = null;

        public static function getInstance() {
            if (CrossoverStrategy::$instance == null) {
                CrossoverStrategy::$instance = new CrossoverStrategy();
            }
            return CrossoverStrategy::$instance;
        }

    }

}
?>