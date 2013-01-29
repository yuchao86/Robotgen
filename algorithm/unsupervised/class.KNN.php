<?php

/**
 * class.KNN.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/unsupervised/
 * @version v1.0
 * @license  GPL     
 *
 * @reference
 * 	-Algorithm Reference
 * @see
 * 	-web Links
 * 	-http://en.wikipedia.org/wiki/K-nearest_neighbor_algorithm
 *
 */
/**
 *  Algorithm Description
 * =================================================================
 */
//namespace Robotgen;


if (!class_exists("KNN")) {

    class KNN {


        /**
         * returns the predictions (sorted array) 
         * in an array ("bestprediction"=>count, "pred2"=>count2...)
         * @param type $xs
         * @param type $ys
         * @param type $row
         * @param type $k
         * @return array
         */
        static function ll_nn_predict($xs, $ys, $row, $k) {
            $distances = self::ll_nearestNeighbors($xs, $row);
            $distances = array_slice($distances, 0, $k); // get top k.

            $predictions = array();
            foreach ($distances as $neighbor => $distance) {
                $predictions[$ys[$neighbor]]++;
            }
            asort($predictions);

            return $predictions;
        }


        /**
         * returns the nearest neighbors for the nth row of $xs 
         * (sorted euclidian distances).
         * @param type $xs
         * @param type $row
         * @return type
         */
        static function ll_nearestNeighbors($xs, $row) {
            $testPoint = $xs[$row];

            $distances = self::_ll_distances_to_point($xs, $testPoint);
            return $distances;
        }

        /**
         * 
         * @param array $xs
         * @param array $x
         * @return array
         */
        static function _ll_distances_to_point($xs, $x) {
            $distances = array();
            foreach ($xs as $index => $xi) {
                $distances[$index] = RCommon::linear_euclidian_distance($xi, $x);
            }
            asort($distances);
            array_shift($distances);   // has "self" as the smallest distance.
            return $distances;
        }

    }

}
?>