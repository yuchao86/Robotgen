<?php

/**
 * class.SetGene.php
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

if (!class_exists("SetGene")) {

    abstract class SetGene implements IGene {

        protected static $set_of_legal_values = array();
        protected $value;

        public function setValue($value) {
            assert(in_array($value, SetGene::$set_of_legal_values));
            $this->value = $value;
        }

        public function getValue() {
            return $this->value;
        }

        public function mutate() {
            $this->value = SetGene::$set_of_legal_values[array_rand(SetGene::$set_of_legal_values, 1)];
        }

        public function __toString() {
            return $this->value;
        }

    }

}
?>