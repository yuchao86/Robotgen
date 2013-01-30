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
 *
 *	$test = new Genetic(29, 47, array(1,2,3), 100);
 *	$test->run();
 *	Constructor expects: __construct(int, int, array(), int)
 *
 */
//namespace Robotgen;

require_once "PHPUnit.php";
require_once "../LearningLibrary.php";


class GeneticTest extends PHPUnit_TestCase {

    private $size = 128;
    private $strassen = false; //true;

    public function testArith() {
        //$gen = new Arithmetic_Genetic(1000, 20, array(3,7,9,1,14), 10);

        $gen = new Arithmetic_Genetic(7, 200, array(1,2,3,4,5,6,7,8,9,1349), 10);
        $result = $gen->run();
        return $this->assertTrue($result == true);
    }

}

/**
 * 	make the test suite object
 */
$suite = new PHPUnit_TestSuite();
$suite->addTest(new GeneticTest('testArith'));

/**
 * 	print the PHPUnit result to HTML
 */
$phpunit = new PHPUnit();
$result = $phpunit->run($suite);
print $result->toHTML();

exit(0);
?>


?>
