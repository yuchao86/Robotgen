<?php

/**
 * class.Linear_StochasticGradientDescent_Regression.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/parametric/regression/
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

if (!class_exists("Linear_StochasticGradientDescent_Regression")) {

// you'll want to setBadIterationsThreshold($autodetect) higher than normal with stochastic gradient descent,
// as it isn't rare to have growing distance.

    class Linear_StochasticGradientDescent_Regression 
                extends Linear_GradientDescent_Regression {

        private $hasShuffled = false;

        /**
         * construct function
         * @param type $xs
         * @param type $ys
         * @param type $initialParameters
         */
        function __construct($xs, $ys, $initialParameters = null) {
            parent::__construct($xs, $ys, $initialParameters);
            $this->setBadIterationsThreshold(100); // set a higher default ootb.
        }

        /**
         * 
         * @return type
         */
        private function stochasticShuffle() {
            if ($this->hasShuffled)
                return;

            // shuffle Xs and Ys simultaneously.
            $temp = array();
            for ($i = 0, $count = count($this->xs); $i < $count; $i++) {
                $temp[$i] = array($this->xs[$i], $this->ys[$i]);
            }  // build a shuffleable array (paired array)

            shuffle($temp);
            for ($i = 0, $count = count($this->xs); $i < $count; $i++) {
                $this->xs[$i] = $temp[$i][0];
                $this->ys[$i] = $temp[$i][1];
            }

            $this->hasShuffled = true;
        }

        /**
         * 
         * @param type $parameters
         * @return type
         */
        protected function iteration($parameters = null) {
            if ($parameters === null)
                $parameters = $this->parameters;

            if (!$this->hasShuffled)
                $this->stochasticShuffle();

            for ($xi = 0; $xi < count($this->ys); $xi++) {
                $temp_parameters = array();
                foreach ($parameters as $index => $param) {
                    $temp_parameters[] = $param - ($this->learningRate * $this->distanceDerivative($index, $xi));
                }
            }

            return $temp_parameters;
        }

        /**
         * 
         * @param type $with_regard_to_index
         * @param type $i
         * @return type
         */
        protected function distanceDerivative($with_regard_to_index, $i = 0) {
            $result = (($this->hypothesis($this->xs[$i]) - $this->ys[$i]) * $this->xs[$i][$with_regard_to_index]);
            return $result;
        }

    }

}
?>
