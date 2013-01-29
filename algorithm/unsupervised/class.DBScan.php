<?php

/**
 * FileName.php
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
 * 	-https://sites.google.com/site/dataclusteringalgorithms/density-based-clustering-algorithm
 *      -http://www.dbs.informatik.uni-muenchen.de/Forschung/KDD/Clustering/index.html
 *
 */
/**
 *  Algorithm Description
 * =================================================================
 *
 * Density-Based Clustering
 *
 * Domenica Arlia, Massimo Coppola. "Experiments in Parallel Clustering with DBSCAN". 
 * Euro-Par 2001: Parallel Processing: 7th International Euro-Par Conference Manchester, 
 * UK August 28–31, 2001, Proceedings. Springer Berlin.
 * Hans-Peter Kriegel, Peer Kröger, Jörg Sander, Arthur Zimek (2011). "Density-based Clustering". 
 * WIREs Data Mining and Knowledge Discovery 1 (3): 231–240. doi:10.1002/widm.30.
 */
//namespace Robotgen;



if (!class_exists("DBScan")) {

    class DBScan {

        /**
         * 
         * @param type $data
         * @param type $e
         * @param type $minimumPoints
         */
        static function ll_dbscan($data, $e, $minimumPoints = 10) {
            $clusters = array();
            $visited = array();

            foreach ($data as $index => $datum) {
                if (in_array($index, $visited))
                    continue;

                $visited[] = $index;

                $regionPoints = self::_ll_points_in_region(array($index => $datum), $data, $e);
                if (count($regionPoints) >= $minimumPoints) {
                    $clusters[] = self::_ll_expand_cluster(array($index => $datum), $regionPoints, $e, $minimumPoints, &$visited);
                }
            }
        }

        /**
         * 
         * @param type $point
         * @param type $data
         * @param type $epsilon
         * @return type
         */
        static function _ll_points_in_region($point, $data, $epsilon) {
            $region = array();
            foreach ($data as $index => $datum) {
                if (RCommon::linear_euclidian_distance($point, $datum) < $epsilon) {
                    $region[$index] = $datum;
                }
            }
            return $region;
        }

        /**
         * 
         * @param type $point
         * @param type $data
         * @param type $epsilon
         * @param type $minimumPoints
         * @param type $visited
         */
        static function _ll_expand_cluster($point, $data, $epsilon, $minimumPoints, &$visited) {
            $cluster[] = $point;

            foreach ($data as $index => $datum) {
                if (!in_array($index, $visited)) {
                    $visited[] = $index;
                    $regionPoints = self::_ll_points_in_region(array($index => $datum), $data, $epsilon);

                    if (count($regionPoints) > $minimumPoints) {
                        $cluster = self::_ll_join_clusters($regionPoints, $cluster);
                    }
                }

                // supposed to check if it belongs to any clusters here.
                // only add the point if it isn't clustered yet.
                $cluster[] = array($index => $datum);
            }
        }
        
        /**
         * 
         * @param type $one
         * @param type $two
         * @return type
         */
        static function _ll_join_clusters($one, $two) {
            return array_merge($one, $two);
        }

    }

}
?>
