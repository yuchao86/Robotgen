<?php

/**
 * class.Linear_Regression.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/parametric/
/
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

/*
  Copyright (c) 2013 yuchao86@gmail.com
  See LICENSE for more information.
 */

if (!interface_exists("InterfaceLinear_Regression")) {
    interface InterfaceLinear_Regression {

        public function predict($x);
    }

}
if (!class_exists("Linear_Regression")) {

    abstract class Linear_Regression implements InterfaceLinear_Regression {

        protected $xs;
        protected $ys;
        protected $parameters;
        protected $resetParameters;
        protected $trained;

        abstract public function train();

        /**
         * 
         * @param type $xs
         * @param type $ys
         * @param type $initialParameters
         */
        function __construct($xs, $ys, $initialParameters = null) {
            $this->setData($xs, $ys, $initialParameters);
        }

        /**
         * 
         * @param type $xs
         * @param type $ys
         * @param type $initialParameters
         * @throws ImproperDataException
         */
        public function setData($xs, $ys, $initialParameters = null) {
            if (count($xs) !== count($ys))
                throw new ImproperDataException("Your xs array is not the same length as your ys array. (you don't have 'labels' for all the input data).");

            $this->xs = $xs;
            $this->ys = $ys;

            if ($initialParameters === null)
                $this->parameters = array_fill(0, count($xs[0]), 0);
            else
                $this->parameters = $initialParameters;

            $this->trained = false;
        }

        /**
         * 
         * @return type
         */
        public function getParameters() {
            return $this->parameters;
        }

        /**
         * 
         */
        public function reset() {
            $this->parameters = $this->resetParameters;
        }

        /**
         * adds a one in the first column of each $x { $xs.
         */
        public function addInterceptTerm() {
            for ($i = 0, $count = count($this->xs); $i < $count; $i++) {
                array_unshift($this->xs[$i], 1);
            }
        }

        /**
         * 
         * @param type $x
         * @return boolean
         */
        public function predict($x) {
            if (!$this->trained)
                return false;

            $prediction = 0;
            foreach ($x as $index => $xi) {
                $prediction += $xi * $this->parameters[$index];
            }
            return $prediction;
        }

    }

}
?>
