<?php

/**
 * interface.IGene.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/swarm/
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

if (!interface_exists("IGene")) {

    /** This interface defines a Gene.
     * 
     * Each class, that implements the IGene interface may be added
     * to a chromomsome.
     */
    interface IGene {

        /** Returns the genes value
         * 
         * @return mixed
         */
        public function getValue();

        /** Sets the genes value
         * 
         * @param mixed $value
         */
        public function setValue($value);

        /** Sets the gene to a random new value
         * 
         */
        public function mutate();
    }

}
?>