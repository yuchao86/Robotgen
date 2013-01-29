<?php

/**
 * interface.IFitnessDeterminationStrategy.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/swarm/strategies/
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

if (!interface_exists("IFitnessDeterminationStrategy")) {

    /** This interface defines a fitness determination strategy (fitness function)
     * 
     * The IFitnessDeterminationStrategy interface defines how a chromosomes
     * fitness is determined. 
     */
    interface IFitnessDeterminationStrategy extends ISingleton {

        /** Determines a chromosomes fitness.
         * 
         * This method determines a chromosomes fitness. An implementing class
         * should calculate the given chromosomes fitness and return it to
         * the caller.
         * 
         * @param Chromsome $chromosome
         * @return Float
         */
        public function determineFitness(Chromsome $chromosome);
    }

}
?>