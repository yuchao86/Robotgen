<?php

/**
 * Chromosome_test.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/genetic/
 * @version v1.0
 * @license  GPL
 *
 * @reference
 * 	-Algorithm Reference
 * @see
 * 	-web Links
 * 	-
 *
 */
/**
 *  Algorithm Description
 * =================================================================
 */

//namespace Robotgen;

/**
 *  Algorithm Description
 *=================================================================
 */
//use Robotgen;

require_once "PHPUnit.php";
require_once "../LearningLibrary.php";


class ChromosomeTest extends PHPUnit_TestCase {

    public function testCrossoverStrategy() {

        $male = array('a' => '1');
        $female = array('b' => '2');

        $malechrome = new Chromosome($male);
        $femalechrome = new Chromosome($female);

        //$malechrome ->calculateFitness();
        $strategy = CrossoverStrategy::getInstance();
        /* @var $child Chromosome */

        $child = $strategy -> recombine($malechrome,$femalechrome);

        $populate = new Population();
        $populate->selectChromosome($strategy);

        $bestChromosome = $populate->getBestChromosome();

        $this->assertTrue($bestChromosome);
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 	make the test suite object
 */
$suite = new PHPUnit_TestSuite();
$suite->addTest(new KNNTest('testCrossoverStrategy'));

/**
 * 	print the PHPUnit result to HTML
 */
$phpunit = new PHPUnit();
$result = $phpunit->run($suite);
print $result->toHTML();

exit(0);
?>
