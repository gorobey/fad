<?php
/*
Simple PHP Image Resizer
Copyright (c) 2012 Ryan Fait
www.ryanfait.com

This script is free. If you want to redistribute this script, you must leave the copyright
notice and my website URL with the function.

*/
header("Pragma: public");
header("Cache-Control: max-age = 604800");
header("Expires: ".gmdate("D, d M Y H:i:s", time() + 604800)." GMT");

function thumbnail($image, $width, $height) {

	//if($image[0] != "/") { // Decide where to look for the image if a full path is not given
	//	if(!isset($_SERVER["HTTP_REFERER"])) { // Try to find image if accessed directly from this script in a browser
	//		$image = $_SERVER["DOCUMENT_ROOT"].implode("/", (explode('/', $_SERVER["PHP_SELF"], -1)))."/".$image;
	//	} else {
	//		$image = implode("/", (explode('/', $_SERVER["HTTP_REFERER"], -1)))."/".$image;
	//	}
	//} else {
	//	$image = $_SERVER["DOCUMENT_ROOT"].$image;
	//}
	
	$image_properties = getimagesize($image);
	$image_width = $image_properties[0];
	$image_height = $image_properties[1];
	$image_ratio = $image_width / $image_height;
	$type = $image_properties["mime"];

	if(!$width && !$height) {
		$width = $image_width;
		$height = $image_height;
	}
	if(!$width) {
		$width = round($height * $image_ratio);
	}
	if(!$height) {
		$height = round($width / $image_ratio);
	}

	if($type == "image/jpeg") {
		header('Content-type: image/jpeg');
		$thumb = imagecreatefromjpeg($image);
	} elseif($type == "image/png") {
		header('Content-type: image/png');
		$thumb = imagecreatefrompng($image);
	} else {
		return false;
	}

	$temp_image = imagecreatetruecolor($width, $height);
	imagecopyresampled($temp_image, $thumb, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
	$thumbnail = imagecreatetruecolor($width, $height);
	imagecopyresampled($thumbnail, $temp_image, 0, 0, 0, 0, $width, $height, $width, $height);

	if($type == "image/jpeg") {
		imagejpeg($thumbnail);
	} else {
		imagepng($thumbnail);
	}

	imagedestroy($temp_image);
	imagedestroy($thumbnail);

}

if(isset($_GET["h"])) { $h = $_GET["h"]; } else { $h = 0; }
if(isset($_GET["w"])) { $w = $_GET["w"]; } else { $w = 0; }

thumbnail($_GET["img"], $w, $h);
