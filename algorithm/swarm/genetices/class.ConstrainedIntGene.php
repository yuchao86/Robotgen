<?php

/**
 * class.ConstrainedIntGene.php
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

if (!class_exists("ConstrainedIntGene")) {

    class ConstrainedIntGene extends IntGene {

        private $constrain;

        public function __construct($constrain) {
            $this->constrain = $constrain;
        }

        public function setValue($value) {
            parent::setValue($value % $this->constrain);
        }

    }

}
?>