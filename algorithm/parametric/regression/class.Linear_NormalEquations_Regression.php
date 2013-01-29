<?php

/**
 * class.Linear_NormalEquations_Regression.php
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

namespace Robotgen;

if (!class_exists("Linear_NormalEquations_Regression")) {

    class Linear_NormalEquations_Regression extends Linear_Regression {

        public function train() {
            $this->resetParameters = $this->parameters;

            if (!class_exists("Linear_Matrix"))
                throw new Exception("Missing Linear_Matrix class.");

            $matrix_x = new Linear_Matrix($this->xs);
            $matrix_y = new Linear_Matrix($this->ys);

            $inner = $matrix_x->transpose()->multiply($matrix_x);

            //if($regularization !== null)  // if $regularization > 0, this solves invertibility issues (but not the underlying statistical problems).
            //{
            //   $regularization_matrix = Linear_Matrix::identity();
            //   $regularization_matrix->set(0,0, 0);
            //   $regularization_matrix->scalarMultiply($regularization);
            //   $inner->add($regularization_matrix);
            //}

            $inner = $inner->invert();
            $inner = $inner->multiply($matrix_x->transpose());
            $result = $inner->multiply($matrix_y);

            $return = array();
            for ($i = 0, $count = count($this->xs[0]); $i < $count; $i++) {
                $return[$i] = $result->get($i, 0);
            }

            $this->parameters = $return;
            $this->trained = true;
            return $return;
        }

    }

}
?>