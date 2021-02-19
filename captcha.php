<?php 
	session_start(); 
	function generateRandomString() {
		// $string = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
		$string = rand(1,9);
		return $string;
	}
	$string1 = generateRandomString();
	$string2 = generateRandomString();
	$result = $string1+$string2;
	$string = $string1.'+'.$string2;
	$_SESSION["vercode"] = $result; 
	$height = 25; 
	$width = 60;   
	$image_p = imagecreate($width, $height); 
	$black = imagecolorallocate($image_p, 0, 0, 0); 
	$white = imagecolorallocate($image_p, 255, 255, 255); 
	$font_size = 14; 
	imagestring($image_p, $font_size, 5, 5, $string, $white); 
	imagejpeg($image_p, null, 80); 
?>