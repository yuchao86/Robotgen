<?php

/**
 * interface.IRecombinationStrategy.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/swarm/strategies/
 * @version v1.0
 * @license  GPL     
 *
 * @reference
 * 	-Algorithm Reference
 * @see
 * 	-web Links
 * 	-
 *
 */
/**
 *  Algorithm Description
 * =================================================================
 */

//namespace Robotgen;

if (!interface_exists("IRecombinationStrategy")) {

    /** This interface defines a recombination strategy
     * 
     * The IRecombinationStrategy interface defines how two chromosomes
     * recombinate to a child. 
     */
    interface IRecombinationStrategy extends ISingleton {

        /** Recombine two chromosomes to create a new child chromosome.
         * 
         * This method should return a new chromsome built from it's parents 
         * genes. The parents are passed as $male and $female to the function.
         * There does not need to be a genetic difference between male and 
         * female chromosomes and the recombination does not need to differnciate
         * between both geners. There are only there for the sake of giving both
         * parameters different names.
         * A possible implementation for this Interface could be a Crossover
         * recombination strategy or a Zip recombination strategy.
         * 
         * @param Chromosome $male
         * @param Chromosome $female
         * @return Chromosome
         */
        public function recombine(Chromosome $male, Chromosome $female);
    }

}
?>