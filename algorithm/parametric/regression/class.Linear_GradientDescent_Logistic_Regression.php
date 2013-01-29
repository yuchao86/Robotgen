<?php

/**
 * class.Linear_GradientDescent_Logistic_Regression.php
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


if (!class_exists("Linear_GradientDescent_Logistic_Regression")) {

    class Linear_GradientDescent_Logistic_Regression 
                extends Linear_GradientDescent_Regression {

        /**
         * 
         * @param type $x
         * @param type $threshold
         * @return type
         */
        public function predict($x, $threshold = 0.5) {
            $prob = $this->sigmoid(parent::predict($x));
            if ($threshold === null)
                return $prob;
            return ($prob > $threshold);
        }

        /**
         * 
         * @return type
         */
        protected function distance() {
            $result = 0;

            for ($i = 0, $count = count($this->ys); $i < $count; $i++) {
                // do we need to check for $this->ys[$i] != {0, 1} here?

                $h_xi = $this->hypothesis($this->xs[$i]);
                $result += (((-1) * $this->ys[$i] * log($h_xi)) - (1 - $this->ys[$i]) * log(1 - $h_xi));
            }

            $result /= count($this->ys);

            return $result;
        }

        /**
         * 
         * @param type $row
         * @return type
         */
        protected function hypothesis($row = null) {
            return $this->sigmoid(parent::hypothesis($row));
        }

        /**
         * 
         * @param type $z
         * @return type
         */
        private function sigmoid($z) {
            return (1 / (1 + exp((-1) * $z)));
        }

    }

}
?>