<?php 

/**
 * weihnachtsmilljoeh.php
 *
 * Competing in MinD Milljoeh's Christmas 2021 Brain Teaser
 *
 * PHP version 7.4.0
 *
 * @author     Maren Arnhold <info@kotori.de>
 * @version    1.1
 * @link       https://github.com/mombromse/weihnachtsmilljoeh
 *
 * Requires php_gmp extension 
 * 
 * Usage/instantiation:
 * 
 * $wm = new Weihnachtsmilljoeh(int $lowerthreshold, int $upperthreshold)
 * $wm->calculate();
 * 
 */
 
namespace Kotori; 
 
require("lib/num2text.php");
 
class Weihnachtsmilljoeh 
{
	
	// Upper threshold to explore
	protected int $upperthreshold = 2;
	
	// Lower threshold from which to start
	protected int $lowerthreshold = 1;
	
	// Current high score
	protected string $highscore = "0";


	public function __construct($m,$n){
		
		if ($m > 0) { $this->lowerthreshold = $m; };
		if ($n > 0) { $this->upperthreshold = $n; };
		
	}

	// Collect prime numbers up to int $max, stored in Array $arr. $n has to be 1 at first function call. 
	function getPrimeNumbers($n,$max,&$arr){
			
		if ((int)gmp_nextprime($n) <= $max) { $arr[] = $this->getPrimeNumbers((int)gmp_nextprime($n),$max,$arr)." "; };	
		
		if ($n == 1) { $arr[] = 1; };
		
		return $n;
	
	}
	

	// As we will only accept integer values for division results:
	// Get prime factors for $n and their multiples in order to reduce sensible brute force scope	
	function getPrimeFactorsAndMultiples($n){
		
		$primearray = [$n];
		$this->getPrimeNumbers(1,$n,$primearray);
		$extended_primearray = [];
	
		$i=1;

		$o = $n;

		if ($primearray[0] == $primearray[1]) {
			
			$extended_primearray[] = 1;
			$extended_primearray[] = $n;
			
		} else while(true){

			if ($i == (count($primearray)-1)) { break; };
						
			
			if ((($o/(int)$primearray[$i]) != floor($o/(int)$primearray[$i])) && ($o == $n)) { 
				
				$i++;
				$j = $i;
				
			};
			
			if ((($o/(int)$primearray[$i]) != floor($o/(int)$primearray[$i])) && ($o != $n)) { 
				
				$i++;
				
			};			
			
			if (($o/(int)$primearray[$i]) == floor($o/(int)$primearray[$i])) { 
			
				$extended_primearray[] = $o/(int)$primearray[$i];
				$o = $o/(int)$primearray[$i];
				if ($o == 1) { $i = $j+1; $o = $n; };
							
			};
									
		}
		
		$extended_primearray[] = $n;
		$extended_primearray = array_unique(array_map('intval',$extended_primearray));
		
		return $extended_primearray;		
	
	}
	

	function outputFormatted($os, $osn, $or, $opa, $opb){
		
		$gmp_opa = gmp_init($opa);
		$gmp_opb = gmp_init($opb);		
		$highscore = gmp_init($this->highscore);

		if (gmp_cmp($highscore,($gmp_opa+$gmp_opb)) == -1) { 
		
			$this->highscore = gmp_strval($gmp_opa+$gmp_opb);
			$points = "***".gmp_strval($gmp_opa+$gmp_opb).", neuer Highscore.***";
			echo $osn." = ".$or." ____ ".$os." (".mb_strlen($os)." Buchstaben). ____ Punktwert: ".$points."\r\n";
			
		};
	
		
	}
	

	function calculateAddition($n){

		for ($j=(max(1,($n-10000)));$j<$n;$j++){

			$op_string = num2text((int)$n)."plus".num2text((int)$j);			
			$op_string_numeric = $n." + ".$j;			
			$op_result = $n + $j;		
			
			if (mb_strlen($op_string) == $op_result) { $this->outputFormatted($op_string,$op_string_numeric,$op_result,(int)$n,(int)$j); };

		}
	
	}
	
	
	function calculateSubtraction($n){
				
		for ($j=(max(1,($n-10000)));$j<$n;$j++){

			$op_string = num2text($n)."minus".num2text($j);
			$op_string_numeric = $n." - ".$j;
			$op_result = $n - $j;
			
			if (mb_strlen($op_string) == $op_result) { $this->outputFormatted($op_string,$op_string_numeric,$op_result,(int)$n,(int)$j); };
						
		}		
		
	}
	
	
	function calculateDivision($n){
		
		
		$extended_primearray = $this->getPrimeFactorsAndMultiples($n);		
			
		foreach ($extended_primearray as $pa){
			
			$op_string = num2text($n)."geteiltdurch".num2text((int)$pa);		
			$op_string_numeric = $n." / ".(int)$pa;		
			$op_result = $n / (int)$pa;
			
			if ($op_result == floor($op_result)) { 	
			
				if (mb_strlen($op_string) == $op_result) { $this->outputFormatted($op_string,$op_string_numeric,$op_result,(int)$n,(int)$pa); };
				
			};
		
		}		
		
	}
	
	
	function calculateMultiplication($n){
				
		for ($j=(max(1,($n-10000)));$j<$n;$j++){

			$op_string = num2text($n)."mal".num2text($j);
			$op_string_numeric = $n." * ".$j;
			$op_result = $n * $j;
			
			if (mb_strlen($op_string) == $op_result) { $this->outputFormatted($op_string,$op_string_numeric,$op_result,(int)$n,(int)$j); };
			
		}			
			
	}


	public function calculate(){
		
		for ($i=$this->lowerthreshold;$i<=$this->upperthreshold;$i++){
			
			// For larger values of $i, addition and multiplication are negligible as operation 
			// string length in the German language will never be sufficient to match the result.
			// Also, division will not yield large scores in comparison to subtraction, 
			// so we will ignore it as well.
			
			if ($i < 100) {
				
				$this->calculateAddition($i);											
				$this->calculateMultiplication($i);
				
			}
			
			if ($i < 1000) {
			
				$this->calculateDivision($i);	
				
			}
			
			$this->calculateSubtraction($i);
									
		};
		
	}

}


