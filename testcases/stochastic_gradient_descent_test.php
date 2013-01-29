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

require_once dirname(__FILE__) . "/../lib/parametric/regression.php";

class StochasticGradientDescentTest extends PHPUnit_TestCase {

    public function testGradientDescent() {
        $xs = array(
            array(1, 2, 2, 2),
            array(1, 3, 3, 3),
            array(1, 4, 4, 4),
            array(1, 5, 5, 5)
        );
        $ys = array(6, 9, 12, 15);
        $parameters = array(0, 0, 0, 0);

        $gd = new LL_StochasticGradientDescent_Regression($xs, $ys, $parameters);
        $gd->setBadIterationsThreshold(5000);
        $gd->setLearningRate(0.02);
        $gd->setRepetitions(1000);
        $gd->train();

        //$results = $gd->getParameters();

        $estimates = array();
        foreach ($xs as $index => $x_row) {
            $estimates[$index] = $gd->predict($x_row);
        }

        $threshold = 0.10;
        foreach ($ys as $index => $y) {
            $high_bound = $y * (1 + $threshold);
            $low_bound = $y * (1 - $threshold);

            $this->assertTrue($estimates[$index] > $low_bound && $estimates[$index] < $high_bound);
        }
    }

}

/**
 * 	make the test suite object
 */
$suite = new PHPUnit_TestSuite();
$suite->addTest(new StochasticGradientDescentTest('testGradientDescent'));


/**
 * 	print the PHPUnit result to HTML
 */
$phpunit = new PHPUnit();
$result = $phpunit->run($suite);
print $result->toHTML();

exit(0);
?>