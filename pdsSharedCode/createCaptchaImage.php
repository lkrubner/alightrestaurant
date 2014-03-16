<?php



function createCaptchaImage($randomNumber=false) {
	// 06-08-08 - I'm pulling this code out of createCaptcha so it can live
	// by itself. I want to use createCaptcha to create numbers for comments
	// on public weblogs. So this needs to be separate. 

	global $controller; 

	if (!$randomNumber) {
		$controller->error("In createCaptchaImage we expected the function to be given a number, but it wasn't."); 
		return false; 
	}


	//03-03-07 This code creates an image out of the random number that we just generated and display it in
	//an image that is written to the server. The image is called  "createCaptcha.jpg"
	$font  = 4;
	$width  = ImageFontWidth($font)* strlen($randomNumber);
	$height = ImageFontHeight($font) ;

	$im = ImageCreateFromjpeg("captcha.jpg");
	$x=imagesx($im)-$width ;
	$y=imagesy($im)-$height;
	$background_color = imagecolorallocate ($im, 255, 255, 255); //white background
	$text_color = imagecolorallocate ($im, 0, 0, 0);//black text
	imagestring ($im, $font, $x, $y,  $randomNumber, $text_color);
 
	
	imagejpeg ($im, "./createCaptcha.jpg");
	@ chmod("./createCaptcha.jpg", 0777);  // octal; correct value of mode

	// 03-27-07 - we were using a relative link in this next image tag, but we
	// need to not do that. The problem is that I want to call this function from
	// iHanuman: 
	//
	// http://www.ihanuman.com/accumulistForm.php
	//
	// the form on that page is going to call this url: 
	//
	// http://www.accumulist.com/output.php?choiceMade=createCaptcha&whatPage=none
	//
	// The image tag that we need to create needs to reference the domain name.
	//
	//
	// 09-15-07 - on SecondRoad, we can use a relative link. 
	echo "<img src='createCaptcha.jpg' />";

}



?>