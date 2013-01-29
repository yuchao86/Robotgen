<?php

/**
 * class.RCommon.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/accessory/
 * @version v1.0
 * @license  GPL     
 *
 * @reference
 * 	-Algorithm Reference
 * @see
 * 	-web Links
 * 	-http://baike.baidu.com/view/32243.htm
 *
 */
/**
 *  Algorithm Description
 * =================================================================
 */

//namespace Robotgen;

if (!class_exists("RCommon")) {

    class RCommon {
        /**
         * 线性同余发生器
         * @param int $min
         * @param int $max
         * @return number float
         */
        static function linear_randomf($min, $max) {
            return $min + lcg_value() * abs($max - $min);
        }
        
        /**
         * linear transpose 矩阵转置
         * @param array $rows
         * @return array
         */
        static function linear_transpose($rows) {
            $columns = array();
            for ($i = 0; $i < count($rows); $i++) {
                for ($k = 0; $k < count($rows[$i]); $k++) {
                    $columns[$k][$i] = $rows[$i][$k];
                }
            }
            return $columns;
        }

        /**
         * average array value
         * @param array $array
         * @return array_sum/count
         */
        static function linear_mean($array) {
            return array_sum($array) / count($array);
        }
        /**
         * variance 线性方程组方差
         * @param array $array
         * @return float number
         */
        static function linear_variance($array) {
            $mean = self::linear_mean($array);

            $sum_difference = 0;
            $n = count($array);

            for ($i = 0; $i < $n; $i++) {
                $sum_difference += pow(($array[$i] - $mean), 2);
            }

            $variance = $sum_difference / $n;
            return $variance;
        }

        /**
         * 欧几里得几何学的距离
         *     -----------------------
         * /\ / (x`-y`)^ +(x`-y`)^+...
         * @param vector $a
         * @param vector $b
         * @return number
         */
        static function linear_euclidian_distance($a, $b) {
            if (count($a) != count($b))
                return false;

            $distance = 0;
            for ($i = 0; $i < count($a); $i++) {
                $distance += pow($a[$i] - $b[$i], 2);
            }

            return sqrt($distance);
        }

    }

}
?>
