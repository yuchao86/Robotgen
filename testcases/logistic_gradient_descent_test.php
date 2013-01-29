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
 namespace Robotgen;
 
require_once "PHPUnit.php";

require_once "../lib/parametric/regression.php";

class LogisticGradientDescentTest extends PHPUnit_TestCase {

    // These are valid tests because the data are loaded such that the results are "obvious" and perfectly predictable by the algorithm
    public function testLogisticGradientDescentExperimental() {
        $xs = array(
            array(1, 1, 2, 2),
            array(1, 1, 3, 0),
            array(1, 2, 4, 1),
            array(1, 3, 5, 9)
        );
        $ys = array(1, 1, 0, 1);

        $lgd = new LL_GradientDescent_Logistic_Regression($xs, $ys);
        $lgd->setLearningRate(0.03);
        $lgd->setRepetitions(1000);
        $lgd->train();

        foreach ($xs as $index => $x) {
            $prediction = $lgd->predict($x);
            $this->assertTrue((bool) $ys[$index] == $prediction);
        }

        $xs = array(
            array(1, 1, 2, 2, 7, 8, 1),
            array(1, 4, 3, 5, 9, 4, 1),
            array(1, 0, 1, 1, 0, 0, 2), // fail row
            array(1, 3, 5, 9, 3, 5, 2),
            array(1, 4, 2, 2, 7, 8, 1),
            array(1, 2, 3, 5, 9, 4, 1),
            array(1, 0, 0, 1, 0, 0, 2), // fail row
            array(1, 7, 5, 9, 3, 5, 2)
        );
        $ys = array(1, 1, 0, 1, 1, 1, 0, 1);

        $lgd->setData($xs, $ys);
        $lgd->train();

        foreach ($xs as $index => $x) {
            $prediction = $lgd->predict($x);
            $this->assertTrue((bool) $ys[$index] == $prediction);
        }
    }

}

/**
 * 	make the test suite object
 */
$suite = new PHPUnit_TestSuite();
$suite->addTest(new LogisticGradientDescentTest('testLogisticGradientDescentExperimental'));


/**
 * 	print the PHPUnit result to HTML
 */
$phpunit = new PHPUnit();
$result = $phpunit->run($suite);
print $result->toHTML();

exit(0);
?>