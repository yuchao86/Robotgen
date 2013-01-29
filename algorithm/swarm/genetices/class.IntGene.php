<?php

/**
 * class.IntGene.php
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

if (!class_exists("IntGene")) {

    class IntGene implements IGene {

        private $value;

        public function getValue() {
            return $this->value;
        }

        public function setValue($value) {
            $this->value = $value;
        }

        public function mutate() {
            $this->setValue(rand());
        }

        public function __toString() {
            return $this->value;
        }

    }

}
?>