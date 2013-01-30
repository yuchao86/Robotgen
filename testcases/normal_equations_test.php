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
// namespace Robotgen;

require_once "PHPUnit.php";

require_once "../LearningLibrary.php";

class NormalEquationsTest extends PHPUnit_TestCase {

    function testNormalRegression() {

        $xs = array(
            array(1, 1),
            array(1, 2),
            array(1, 3),
            array(1, 4)
        );
        $ys = array(2, 4, 6, 8);

        $ner = new Linear_NormalEquations_Regression($xs, $ys);
        $result = $ner->train();
        var_dump($result);
        $this->assertEquals($result[0], 0);
        $this->assertEquals($result[1], 2.0);
    }

}

/**
 * 	make the test suite object
 */
$suite = new PHPUnit_TestSuite();
$suite->addTest(new NormalEquationsTest('testNormalRegression'));


/**
 * 	print the PHPUnit result to HTML
 */
$phpunit = new PHPUnit();
$result = $phpunit->run($suite);
print $result->toHTML();

exit(0);
?>
