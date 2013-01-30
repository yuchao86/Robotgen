<?php
/**
 * FileName.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/genetic/
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

require_once "PHPUnit.php";
require_once "../LearningLibrary.php";

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 	make the test suite object
 */
$suite = new PHPUnit_TestSuite();
$suite->addTest(new KMeansTest('testInitCentroids'));

$suite->addTest(new KMeansTest('testDistanceToCentroid'));

$suite->addTest(new KMeansTest('testFindClosestCentroid'));

$suite->addTest(new KMeansTest('testFlip'));

/**
 * 	print the PHPUnit result to HTML
 */
$phpunit = new PHPUnit();
$result = $phpunit->run($suite);
print $result->toHTML();

exit(0);
?>
