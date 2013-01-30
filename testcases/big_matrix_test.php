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
//use Robotgen;

require_once "PHPUnit.php";
require_once "../LearningLibrary.php";
set_time_limit(0);

class MatrixTest extends PHPUnit_TestCase {

    private $size = 128;
    private $strassen = false; //true;

    public function testMultiply() {
        $identity = $this->getIdentityMatrix($this->size);
        $array = array();
        for ($i = 0; $i < $this->size; $i++) {
            for ($j = 0; $j < $this->size; $j++) {
                $array[$i][$j] = rand();
            }
        }

        $matrix = new Linear_Matrix($array);

        if ($this->strassen)
            $result = $matrix->strassenMultiply($identity);
        else
            $result = $matrix->naiveMultiply($identity);
        return $this->assertTrue($result == true);
    }

    // test helpers
    private function getIdentityMatrix($size = 3) {
        $result = array();
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $result[$i][$j] = ($j == $i);
            }
        }
        return new Linear_Matrix($result);
    }

}

/**
 * 	make the test suite object
 */
$suite = new PHPUnit_TestSuite();
$suite->addTest(new MatrixTest('testMultiply'));

/**
 *  print the PHPUnit result to HTML
 */
$phpunit = new PHPUnit();
$result = $phpunit->run($suite);
print $result->toHTML();

exit(0);
?>
