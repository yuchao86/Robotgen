<?php
/**
 * LearningLibrary.php
 * Robotgen_autoload
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/
 * @version v1.0
 * @license  GPL     
 *
 * @reference
 *	-Algorithm Reference
 * @see
 *	-web Links
 *	-
 *
 */
/**
 *  Algorithm Description
 *=================================================================
 */
 //namespace Robotgen;
/*
  Copyright (c) 2013 Yu Chao <yuchao86@gmail.com> - under the GNU GPL.
  See LICENSE for more information.
 */



function Robotgen_autoload($className) {

    $libdir = dirname(__FILE__);
    $directories = array(
        "",
        "algorithm/",
        "algorithm/accessory/",
        "algorithm/genetic/",
        "algorithm/parametric/",
        "algorithm/parametric/regression/",
        "algorithm/swarm/genetices/strategies/",
        "algorithm/swarm/genetices/strategies/concrete/",
        "algorithm/unsupervised/",
    );

    $fileNameFormats = array(
        "interface.%s.php",
        "class.%s.php",
    );

    foreach ($directories as $directory) {
        foreach ($fileNameFormats as $fileNameFormat) {
            $path = $libdir . "/" . $directory . sprintf($fileNameFormat, $className);
            if (file_exists($path)) {
                echo $path."<br/>";
                require_once $path;
                return;
            }
        }
    }
}
//pl_autoload_extensions(".php"); // comma-separated list
//spl_autoload_register(__NAMESPACE__."\Robotgen_autoload");
spl_autoload_register("Robotgen_autoload");

?>
