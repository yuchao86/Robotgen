<?php

/**
 * class.AlphaStrategy.php
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

if (!class_exists("AlphaStrategy")) {

    /** Implements the ISelectionStrategy interface to select a chromosome from a population
     * 
     * AlphaStrategy selects the single best chromosome from a population. As every
     * other ISelectionStrategy it is implemented as a singleton class.
     */
    class AlphaStrategy implements ISelectionStrategy {

        /** Selects a chromosome from the given population and returns it.
         * 
         * @param Population $population
         * @returns Chromosome
         * @see classes/ga/strategies/ISelectionStrategy#select()
         */
        public function select(Population $population) {
            return $population->getBestChromosome();
        }

        /** Class Contrsuctor. Protected to prevent direct instanciation, 
         * AlphaStrategy is a singleton class.
         * 
         * @return AlphaStrategy
         */
        protected function __construct() {
            
        }

        /** Holds the singleton instance.
         * 
         * @var AlphaStrategy
         */
        private static $instance = null;

        /** Returns the singleton instance and creates one if none exists yet.
         * 
         * @return AlphaStrategy
         */
        public static function getInstance() {
            if (AlphaStrategy::$instance == null) {
                AlphaStrategy::$instance = new AlphaStrategy();
            }
            return AlphaStrategy::$instance;
        }

    }

}
?>