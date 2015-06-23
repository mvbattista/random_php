<?php

function is_a_number_prime($input){
	for ($i = 2; $i<$input; $i++) {
		if ($input % $i == 0) {
			return false;
		}
	}
	return true;
}

var_dump(is_a_number_prime(7));
var_dump(is_a_number_prime(64));
?>