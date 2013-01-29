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

class GradientDescentTest extends PHPUnit_TestCase {

    public function testPredict() {
        $xs = array(
            array(1, 2, 2, 2),
            array(1, 3, 3, 3),
            array(1, 4, 4, 4),
            array(1, 5, 5, 5)
        );
        $ys = array(6, 9, 12, 15);
        $parameters = array(0, 0, 0, 0);

        $gd = new LL_GradientDescent_Regression($xs, $ys, $parameters);
        $gd->setLearningRate(0.01);
        $gd->setRepetitions(10000);
        $gd->train();

        $results = $gd->getParameters();

        $estimates = array();
        foreach ($xs as $index => $x_row) {
            $estimates[$index] = 0;
            foreach ($x_row as $paramIndex => $xi) {
                $estimates[$index] += $results[$paramIndex] * $xi;
            }
        }  // manually computed estimates.


        foreach ($estimates as $index => $est) {
            $this->assertEquals($gd->predict($xs[$index]), $est);
        }
    }

    public function testGradientDescent() {
        $xs = array(
            array(1, 2, 2, 2),
            array(1, 3, 3, 3),
            array(1, 4, 4, 4),
            array(1, 5, 5, 5)
        );
        $ys = array(6, 9, 12, 15);
        $parameters = array(0, 0, 0, 0);

        $gd = new LL_GradientDescent_Regression($xs, $ys, $parameters);
        $gd->setLearningRate(0.01);
        $gd->setRepetitions(10000);
        $gd->train();

        $results = $gd->getParameters();

        $this->assertLessThan(0.01, array_shift($results));
        foreach ($results as $coefficient) {
            $this->assertGreaterThan(0.999, $coefficient);
        }

        $gd->reset();
        $gd->setLearningRate(0.03);
        $gd->setRepetitions(5);

        $results = $gd->train();

        $this->assertLessThan(0.3, array_shift($results));
        $this->assertGreaterThan(0.2, array_shift($results));
        foreach ($results as $coefficient) {
            $this->assertGreaterThan(0.99, $coefficient);
        }
    }

    public function assertLessThan($a = 0, $b = 0) {
        return $a < $b;
    }

    public function assertGreaterThan($a = 0, $b = 0) {
        return $a > $b;
    }

}

/**
 * 	make the test suite object
 */
$suite = new PHPUnit_TestSuite();
$suite->addTest(new GradientDescentTest('testPredict'));

$suite->addTest(new GradientDescentTest('testGradientDescent'));


/**
 * 	print the PHPUnit result to HTML
 */
$phpunit = new PHPUnit();
$result = $phpunit->run($suite);
print $result->toHTML();

exit(0);
?>