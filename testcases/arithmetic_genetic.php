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
 * PHPGen 
===========
> A Genetic Programming Example in PHP.

<a href="http://mvin.net/scripts/rungen.php">View Live</a>
----------------------------------------------------
Install from https://github.com/Mvin/PHPGen

Usage
----------------------------------------------------
* Include or Require the class in your php file.

* Create a new instance of the class with the required parameters:

```php
$test = new Genetic(10, 20, array(1,2,3), 10);
```

* Call the run() method on the instance of the class.

```php
$test->run();
```

* run() will echo results to the screen in the following format:
	
>	Generations: 1000 Chromosones: 50, 
>	Solving for 12 Using only: 1's ,2's ,3's , 
>
>	Highest Fitness Achieved: 
>	Result: return 3*3+3;
>
>	Answer: 12 Fitness: 92

Constructor Docs
----------------------------------------------------
* 1st Parameter: Number of generations
* 2nd Parameter: Number of Chromosomes
* 3rd Parameter: Array of seed numbers, followed by the desired result
* 4th Parameter: The maximum representation size

```php	
Constructor expects: __construct(int, int, array(), int)
```	

Credits	
-------
>Author: Matthew Vincent (<a href="http://mvin.net">Mvin.net</a>) 2012
 * 
 */
namespace Robotgen;

include "genV2.php";

$test = new Genetic(1000, 20, array(3,7,9,1,14), 10);

$test->run();



?>