<?php

/**
 * class.Population.php
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



function PGA_loadClass($className) {

    $libdir = dirname(__FILE__);
    $directories = array(
        "",
        "genes/",
        "strategies/",
        "strategies/concrete/"
    );

    $fileNameFormats = array(
        "interface.%s.php",
        "class.%s.php",
    );

    foreach ($directories as $directory) {
        foreach ($fileNameFormats as $fileNameFormat) {
            $path = $libdir . "/" . $directory . sprintf($fileNameFormat, $className);
            if (file_exists($path)) {
                require_once $path;
                return;
            }
        }
    }
}

spl_autoload_register("PGA_loadClass");
?>