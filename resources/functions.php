<?php

//Basic functions


//redirect to new page

	function redirect($location){
	
		header("Location: $location ");
		
	}
	
	
//check if text color should be white or black

function getContrastColor($hexcolor) {               
    $r = hexdec(substr($hexcolor, 1, 2));
    $g = hexdec(substr($hexcolor, 3, 2));
    $b = hexdec(substr($hexcolor, 5, 2));
    $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    return ($yiq >= 128) ? 'black' : 'white';
}    