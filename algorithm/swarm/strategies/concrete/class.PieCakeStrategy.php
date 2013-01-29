<?php

/**
 * class.PieCakeStrategy.php
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

if (!class_exists("PieCakeStrategy")) {

    class PieCakeStrategy implements ISelectionStrategy {

        public function select(Population $population) {
            $rand = RCommon::linear_randomf(0, $population->getTotalFitness());
            $population->rewind();
            while ($population->valid() && $rand > $population->current()->getFitness()) {
                $rand -= $population->current()->getFitness();
                $population->next();
            }
            if ($rand > 0) { // might happen due to numeric effects
                $population->end(); // return last element if it happens
            }
            return $population->current();
        }

        protected function __construct() {
            
        }

        private static $instance = null;

        public static function getInstance() {
            if (PieCakeStrategy::$instance == null)
                PieCakeStrategy::$instance = new PieCakeStrategy();
            return PieCakeStrategy::$instance;
        }

    }

}
?>