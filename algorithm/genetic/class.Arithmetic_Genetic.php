<?php

/**
 * class.Arithmetic_Genetic.php
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
 * 	-http://en.wikipedia.org/wiki/Genetic_algorithms
 *      _GA zh http://baike.baidu.com/view/45853.htm
 *
 */
/**
 *  Algorithm Description
 * =================================================================
 */

//namespace Robotgen;

if (!class_exists("Arithmetic_Genetic")) {

    class Arithmetic_Genetic {

        private $gen = "";
        private $numC = "";
        private $rep = "";
        private $seed = array();
        private $chromo = array();
        private $fitness = array();
        private $preserve = array();
        private $preserved = array();
        private $operations = array('+', '*', '-', '/');

        
        /**
         * Constructor accepting all the needed values for the algorithm to run.
         * @param type $genIn
         * @param type $numCIn
         * @param type $seedIn
         * @param type $repIn
         * @return void construct
         */
        function __construct($genIn, $numCIn, $seedIn, $repIn) {
            $this->gen = $genIn;
            $this->numC = $numCIn;
            $this->seed = $seedIn;
            $this->rep = $repIn;
        }
        
        /**
         * Completes a full run of the algorithm for 
         * $this->gen number of generations.
         * Echos to the screen the highest fitness achieved 
         * with its cooredsponding equation.
         * run() - Run the genetic algorithm, this function 
         * returns nothing and accepts no input.
         * @return void debug test run function
         */
        public function run() {
            //Some Printing.
            echo "Generations: " . $this->gen . " Chromosones: " . $this->numC . ", 
		 <p>Solving for <b>" . $this->seed[sizeof($this->seed) - 1] . 
                    "</b> Using only: ";
            for ($i = 0; $i < (sizeof($this->seed) - 1); $i++) {
                echo "<b>" . $this->seed[$i] . "</b>'s ,";
            }
            echo "</p>";

            //This loop populates the chromosomes with initial values. 
            // Also sets default fitness to 0, and preserved status to false.
            for ($i = 0; $i < $this->numC; $i++) {
                $this->chromo[$i] = array();
                $this->populate($i);
                $this->fitess[] = 0;
                $this->preserve[] = false;
            }

            //The main loop of the algorithm, it runs for generation number of times.
            for ($x = 0; $x < $this->gen; $x++) {

                //Cycle through each chromosome; mutate it, and recalculate its fitness.
                for ($i = 0; $i < sizeof($this->chromo); $i++) {
                    if ($this->preserve[$i] != true)
                        break;

                    $this->mutate($i);
                    //Reset array keys, so when elements get 
                    //taken away keys are not unordered. 
                    $this->chromo[$i] = array_values($this->chromo[$i]);
                    $this->fitness($i);

                    //If for some reason the last item in a gene is not numeric we die.
                    if (is_numeric($this->chromo[$i][sizeof($this->chromo[$i]) - 1])) {
                        
                    } else {
                        die();
                    }
                }

                //Call getBest, to sort the array of chromosomes, 
                //and crossover the top ranking ones.
                $this->getBest();
            }

            //Lets find the overall best fitness.
            //call mostFit, to find the gene with the highest fitness.
            $mostFit = $this->mostFit();

            //Lets print our results.
            echo "Highest Fitness Achieved: <br />";
            $this->printIt($mostFit);
        }

        /**
         * mostFit() - return the most fit gene.
         * Returns the index of the gene with the highest fitness.
         * @return type
         * @return int fit gene
         */
        private function mostFit() {
            //Set the initial biggest to zero, so we have to take the first fitness.
            $mostFit = 0;
            //Cycle through each gene and checks its fitness against mostFit.
            //If the fitness is larger than the current fitness at mostFit, take it instead.
            for ($i = 0; $i < sizeof($this->chromo); $i++) {
                $mostFit = ($this->fitness($i) > $this->fitness($mostFit)) ? $i : $mostFit;
            }

            return $mostFit;
        }

        /**
         * populate(int) - Populate takes in an index and populates the gene array at that index.
         * Populates each chromosome randomly, 
         * based on what is availible in the seeds, and operations arrays.
         * @param type $x
         * @return void populate the sub chromosome
         */
        private function populate($x) {
            $added = 0;

            //We will populate each chromosome only up until the max representation.
            //In this case since we are adding a number(1) and an operation (+) each time
            //we only loop for ($this->rep/2), we also subtract 1 since its 0 indexed.
            for ($i = 0; $i < (($this->rep / 2 - 1)); $i++) {
                //Make a 50/50 choice to add anything or not
                //if rand returns 0, we are essentialy adding nothing to the gene this iteration.
                if (rand(0, 1) == 1) {
                    //If we are adding the first element, it must be a number.
                    if ($added != 1) {
                        $this->chromo[$x][] = $this->seed[rand(0, sizeof($this->seed) - 2)];
                    }
                    //Add a operation first, followed by a number.
                    $this->chromo[$x][] = $this->operations[rand(0, sizeof($this->operations) - 1)];
                    $this->chromo[$x][] = $this->seed[rand(0, sizeof($this->seed) - 2)];
                    //Set added to one because we have already added some elements.
                    $added = 1;
                }
            }
            //If the the gene currently has no elements, we must add some before we end the function.
            //We add 3 total elements, 2 numbers, and a middle operation.
            if (sizeof($this->chromo[$x]) == 0) {
                $this->chromo[$x][] = $this->seed[rand(0, sizeof($this->seed) - 2)];
                $this->chromo[$x][] = $this->operations[rand(0, sizeof($this->operations) - 1)];
                $this->chromo[$x][] = $this->seed[rand(0, sizeof($this->seed) - 2)];
            }
        }

        /**
         * Calculate and return the Chromosomes fitness value of the (index) chromosome array.
         * fitness(int) - Calculates the fitness of the current chromosome.
         * @param type $x
         * @return int the fitness result
         */
        public function fitness($x) {
            //Reset the fitness to zero, since we dont yet know what it is.
            $this->fitness[$x] = 0;
            //Turn the array into a string, so we can evaluate it.
            $equation = implode('', $this->chromo[$x]);
            //Add a return statement so the eval returns an answer.
            $equation = "return " . $equation . ";";
            //Run the equation like it was a php snippet, return anwser to $answer.
            $answer = eval($equation);

            //If resulting answer is equal to the desired result, make the fitness almost perfect.
            if ($answer == $this->seed[sizeof($this->seed) - 1])
                $this->fitness[$x] = 90;
            //If the answer recieved is less than the desired result, give it a moderate fitness
            //based on the distance from the desired result.
            else if ($answer < $this->seed[sizeof($this->seed) - 1])
                $this->fitness[$x] = ($answer * 50) / $this->seed[sizeof($this->seed) - 1];
            //If the chromosome size is the smallest possible give it an additional 10 fitness. 
            //This is meant to reward shortest answers, however how do we know the shortest?
            if (sizeof($this->chromo[$x]) <= 3)
                $this->fitness[$x] += 10;
            //Otherwise we add on some additional fitness which scales by the size of the
            //current equation, less length = more fitness.
            else
                $this->fitness[$x] += $this->rep / sizeof($this->chromo[$x]);
            //If fitness is perfect. Preserve this perfect specimen.
            if ($this->fitness[$x] == 100)
                $this->preserved[] = $this->chromo[$x];
            //If it is 90 or higher, preserve it from further mutation.
            else if ($this->fitness[$x] > 90)
                $this->preserve[$x] = true;
            //Otherwise make sure it is not preserved.
            else
                $this->preserve[$x] = false;

            return $this->fitness[$x];
        }

        /**
         * mutate(int) - Mutates the gene at the (index) in the chromosomes array.
         * The Mutating function, accepts an index and mutates the gene at the index.
         * @param type $x
         * @return void
         */
        public function mutate($x) {

            //if the gene at that index is marked as preserved we exit, and leave it unchanged.
            if ($this->preserve[$x] == true)
                return;

            //Pick a random spot to do the mutating at
            $spot = rand(0, sizeof($this->chromo[$x]) - 1);
            //Pick a type of mutating we are doing
            // 0 - We take away elements from the gene.
            // 1 - We add elements to the gene.
            // 2, 3, 4, 5 - We toggle an element with another from its base array.
            // The rand is done from 0,5 to ensure that the ratio of normal mutation
            //  to forced mutation is not extreme.
            $type = rand(0, 5);
            switch ($type) {
                //Remove elements from the gene.
                //If the current size of the gene is greater than 3 we can do a mutation of this type.
                case 0: if (sizeof($this->chromo[$x]) > 3) {
                        //If the desired spot to mutate is at the end of the string, we have to remove it 
                        //and the one before.
                        if ($spot == (sizeof($this->chromo[$x]) - 1)) {
                            unset($this->chromo[$x][$spot - 1]);
                            //Otherwise we must remove the element at $spot and its successor. 
                        } else {
                            unset($this->chromo[$x][$spot + 1]);
                        }
                        unset($this->chromo[$x][$spot]);
                    }
                    break;
                //Add elements to the gene
                //We only add elements if the gene is currently less than the max representation size. 
                case 1: if ($this->chromo[$x] <= $this->rep) {
                        //We must add both a operation and a number, these are added to the end of the array.
                        $this->chromo[$x][sizeof($this->chromo[$x]) - 1] = 
                                $this->operations[rand(0, sizeof($this->operations) - 1)];
                        $this->chromo[$x][sizeof($this->chromo[$x]) - 1] = 
                                $this->seed[rand(0, sizeof($this->seed) - 2)];
                    }
                    break;
                //In all other cases we are doing normal mutation.
                default:
                    //If the spot is odd
                    if ($spot & 1)
                    //Add a operation
                        $this->chromo[$x][$spot] = $this->operations[rand(0, sizeof($this->operations) - 1)];
                    else //Otherwise add a number.
                        $this->chromo[$x][$spot] = $this->seed[rand(0, sizeof($this->seed) - 2)];
                    break;
            }
        }

        /**
         * getBest() - Sorts the array to find the best parent chromosomes and does the resulting crossover.
         * Sorts all chromosomes by fitness, uses ranking to decide which chromosomes get crossover treatment. 
         * @return void echo best value
         */
        public function getBest() {
            //Make a temporaty array of all the fitness, for easy sorting.
            for ($i = 0; $i < sizeof($this->chromo); $i++) {
                $temp[] = $this->fitness($i);
            }
            //Uses the PHP function arsort(), to sort the temporary array in decending order. The keys are left intact. 
            arsort($temp);
            //A temporary copy of the chromosome, is to be inserted in this variable.
            $tempChromo = array();

            //Cycle through and crossover only the (number of chromosomes/3) best chromosomes.
            for ($i = 0; $i < ($this->numC / 3); $i++) {
                //Since the key's are no longer in order; to access the array we use the php array pointer.
                $key = each($temp);
                //We want the array key
                $key = $key['key'];
                //Get the second parents key
                $second = each($temp);

                //Copy the parents into a temporary copy.
                $tempChromo[] = $this->chromo[$key];
                $tempChromo[] = $this->chromo[$second['key']];

                //Pass both parents into the crossover function
                //The function returns two arrays, the list() function is used to capture both return values.
                //They are added to the temporary array.
                list($tempChromo[sizeof($tempChromo)], $tempChromo[sizeof($tempChromo) + 1]) = $this->crossover($key, $second['key']);
            }
            //The old chromosome array is replaced by the new one with all the children.
            $this->chromo = $tempChromo;
        }

        /**
         * Accepts two indexes for the parent arrays, returns the children.
         * Takes two parents refrenced by their indexes, crosses them over and returns the resulting children.
         * @param int $parent
         * @param int $myself
         * @return list($child1,$child2) = crossover(int,int); - RETURNS TWO ARRAYS
         */
        public function crossover($parent, $myself) {
            //Make a copy of Parent 1
            $temp1 = $this->chromo[$parent];
            //Make a copy of Parent 2
            $temp2 = $this->chromo[$myself];

            //Find out which of the parents is the smaller one
            //We can only crossover up until the length of the smallest parent.
            $smallest = (sizeof($this->chromo[$myself]) > sizeof($this->chromo[$parent])) ? 
                    sizeof($this->chromo[$parent]) : sizeof($this->chromo[$myself]);

            //Randomly choose the crossover size, ranges from 0 till the smallest size of a parent.
            $size = rand(0, $smallest - 1);

            //Basic bubble swap, up until the size has been met.
            for ($i = 0; $i < $size; $i++) {
                $temp = $temp2[$i];
                $temp2[$i] = $temp1[$i];
                $temp1[$i] = $temp;
            }

            //Return the temporary copies, because they are the children.
            return array($temp2, $temp1);
        }

        /**
         * Prints the Resulting equation, its result, and its fitness for the indexed item.
         * Prints the results of the algorithm for debug.
         * @param type $i
         * @return void Prints to screen
         */
        public function printIt($i) {
            $equation = implode('', $this->chromo[$i]);
            $equation = "return " . $equation . ";";
            $answer = eval($equation);
            echo "<p>Result: <b>" . $equation . "</b> </p> <p>Answer: " . $answer .
                    " Fitness: " . round($this->fitness($i), 3) . "</p>";
        }

    }

}
?>