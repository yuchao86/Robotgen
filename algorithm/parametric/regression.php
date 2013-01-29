<?php
/**
 * FileName.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/genetic/
 * @version v1.0
 * @license  GPL     
 *
 * @reference
 *	-Algorithm Reference
 * @see
 *	-web Links
 *	-
 *
 */
/**
 *  Algorithm Description
 *=================================================================
 */
 namespace Robotgen;

/*
  Copyright (c) 2011 Giuseppe Burtini
  See LICENSE for more information.
 */

interface InterfaceLinear_Regression {

    public function predict($x);
}

abstract class Linear_Regression implements InterfaceLinear_Regression {

    protected $xs;
    protected $ys;
    protected $parameters;
    protected $resetParameters;
    protected $trained;

    abstract public function train();

    function __construct($xs, $ys, $initialParameters = null) {
        $this->setData($xs, $ys, $initialParameters);
    }

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

    public function getParameters() {
        return $this->parameters;
    }

    public function reset() {
        $this->parameters = $this->resetParameters;
    }

    // adds a one in the first column of each $x { $xs.
    public function addInterceptTerm() {
        for ($i = 0, $count = count($this->xs); $i < $count; $i++) {
            array_unshift($this->xs[$i], 1);
        }
    }

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

require_once dirname(__FILE__) . "/regression/gradient_descent.php";
require_once dirname(__FILE__) . "/regression/stochastic_gradient_descent.php";
require_once dirname(__FILE__) . "/regression/logistic.php";
require_once dirname(__FILE__) . "/regression/normal_equations.php";


?>
