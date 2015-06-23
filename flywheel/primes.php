<?php

function iprimes_upto($limit) {
	# Add 2 to the limit provided as keys to an array
    for($i = 2; $i < $limit; $i++) {
        $primes[$i] = true;
    }
 
    for($n = 2; $n < $limit; $n++) {
	    # If that key is still true (prime)
	    if ($primes[$n]) {
		    # Start at $n^2, since everything previous is handled by previous iterations.
	        for ($i = $n*$n; $i < $limit; $i += $n) {
		        $primes[$i] = false;
	        }
	    }
    }
    $result = array();
    return array_keys(array_filter($primes));
}
ini_set('memory_limit', '256M');
/*
	$foo = iprimes_upto(1000000);
print_r($foo);
*/

	
function fib_recursive($i)
{
  if($i == 1 || $i == 2)
    return 1;
  return fib_recursive($i-1)+fib_recursive($i-2);
}
function fib_dynamic($n)
{
  $f = array();
  $f[0] = $f[1] = 1;
  for($i = 2; $i < $n; $i++)
    {
      $f[$i]=$f[$i-1]+$f[$i-2];
    }
  return $f[$n-1];
}
echo fib_dynamic(33)."\n";
echo fib_recursive(33)."\n";
	

?>