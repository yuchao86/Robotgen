<?php

/**
 * class.ColorGene.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/swarm/genetices/
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

if (!class_exists("ColorGene")) {

    class ColorGene extends SetGene {

        public function __construct() {
            ColorGene::$set_of_legal_values =
                    array("green", "red", "blue", "yellow", "purple", "orange");
        }

    }

}
?>