<?php

/**
 * class.Linear_GradientDescent_Regression.php
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

if (!class_exists("Linear_GradientDescent_Regression")) {

    class Linear_GradientDescent_Regression extends Linear_Regression {

        protected $learningRate;
        protected $repetitions;
        protected $badIterationsThreshold = 3;
        protected $convergenceThreshold;

        /**
         * 
         * @return type
         * @throws BadIterationsException
         */
        public function train() {
            $this->resetParameters = $this->parameters;
            $distance = $this->distance();

            $continue = true;
            $badIterationsCount = $iterationsCount = 0;

            do {
                $iterationsCount++;

                $parameters = $this->iteration();
                $newDistance = $this->distance($parameters);

                if ($distance <= $newDistance) {
                    $badIterationsCount++;
                    // line search and backtrack for an appropriate learning rate here.

                    if ($badIterationsCount > $this->badIterationsThreshold)
                        throw new BadIterationsException("Distance is increasing on iterations. You probably want to set a lower learning rate.");
                } else {
                    $badIterationsCount = 0;   // reset bad iterations count on a good iteration.
                }

                if ($this->repetitions !== null) {
                    $continue = ($iterationsCount++ < $this->repetitions);
                } else if (abs(($distance - $newDistance)) < $this->convergenceThreshold) {  // convergence test.
                    $continue = false;
                } else {
                    $continue = true;
                }

                $distance = $newDistance;
                $this->parameters = $parameters;
            } while ($continue);

            $this->trained = true;
            return $parameters;
        }

        /**
         * 
         * @param type $alpha
         */
        public function setLearningRate($alpha) {
            $this->learningRate = $alpha;
        }

        /**
         * 
         * @return type
         */
        public function getLearningRate() {
            return $this->learningRate;
        }

        /**
         * 
         * @param type $reps
         */
        public function setRepetitions($reps) {
            $this->repetitions = $reps;
            $this->autodetectConvergence = false;
        }

        /**
         * 
         * @return type
         */
        public function getRepetitions() {
            return $this->repetitions;
        }

        /**
         * 
         * @param type $autodetect
         */
        public function setConvergenceThreshold($autodetect) {
            $this->convergenceThreshold = $autodetect;
            if ($autodetect)
                $this->repetitions = null;
        }

        /**
         * 
         * @return type
         */
        public function getConvergenceThreshold() {
            return $this->convergenceThreshold;
        }

        /**
         * 
         * @param type $autodetect
         */
        public function setBadIterationsThreshold($autodetect) {
            $this->badIterationsThreshold = $autodetect;
        }

        /**
         * 
         * @return type
         */
        public function getBadIterationsThreshold() {
            return $this->badIterationsThreshold;
        }

        /**
         * 
         * @param type $parameters
         * @return type
         */
        protected function iteration($parameters = null) {
            if ($parameters === null)
                $parameters = $this->parameters;

            $temp_parameters = array();
            foreach ($parameters as $index => $param) {
                $temp_parameters[] = $param - ($this->learningRate * $this->distanceDerivative($index));
            }
            return $temp_parameters;
        }

        /**
         * 
         * @return type
         */
        protected function distance() {
            $result = 0;
            for ($i = 0, $count = count($this->ys); $i < $count; $i++) {
                $result += pow(($this->hypothesis($this->xs[$i]) - $this->ys[$i]), 2);
            }

            return $result;
        }
        
        /**
         * computes gradients by passing in with_regard_to_index.
         * @param type $with_regard_to_index
         * @return type
         */
        protected function distanceDerivative($with_regard_to_index) {
            $data_count = count($this->ys);
            $result = 0;
            for ($i = 0; $i < $data_count; $i++) {
                $result += (($this->hypothesis($this->xs[$i]) - $this->ys[$i]) * $this->xs[$i][$with_regard_to_index]);
            }
            $result *= (1 / $data_count);

            return $result;
        }

        /**
         * 
         * @param type $row
         * @return boolean
         */
        protected function hypothesis($row) {
            if (count($this->parameters) != count($row))
                return false;

            $result = 0;
            for ($i = 0, $count = count($this->parameters); $i < $count; $i++)
                $result += $row[$i] * $this->parameters[$i];

            return $result;
        }

    }

}
if (!class_exists("BadIterationsException")) {

    class BadIterationsException extends Exception {
        
    }

}
if (!class_exists("ImproperDataException")) {

    class ImproperDataException extends Exception {
        
    }

}
?>