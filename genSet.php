<?php
$name = $_POST['name'];
if(isset($_POST['image']) && trim($_POST['image']) != 'undefined') {
	$icon = imagecreatefromstring(base64_decode(explode('base64,', $_POST['image'])[1]));
} else {
	$icon = imagecreatetruecolor(64, 64);
	imagealphablending($icon, false);
	$col=imagecolorallocatealpha($icon, 255, 255, 255, 127);
	imagefilledrectangle($icon, 0, 0, 485, 500, $col);
	imagealphablending($icon, true);
}

$cards = json_decode($_POST['cards']);
$typePrefixes = ['white_', 'black_', 'black2_', 'black3_'];
$typeCounters = [1, 1, 1, 1];
/*header('Content-type: image/png');
//echo generateCard('Cards Against Humanity', 'Passive-aggressive Post-it notes.', 0, $icon);
echo generateBack('Cards Against Humanity', 1); 
die();*/
$file = tempnam('tmp', 'zip');
$zip = new ZipArchive();
$zip->open($file, ZipArchive::OVERWRITE);
foreach($cards as $card) {
	$result = generateCard($name, $card->value, $card->type, $icon);
	$zip->addFromString($typePrefixes[$card->type].$typeCounters[$card->type].'.png', $result);
	$typeCounters[$card->type]++;
}
$zip->addFromString("bc_back.png", generateBack($name, 0));
$zip->addFromString("wc_back.png", generateBack($name, 1));

$zip->close();
header("Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"".$name.".zip\"");
readfile($file);
unlink($file);

function generateBack($setname, $color) {
	$img = imagecreatetruecolor(825, 1125);
	imagefill($img, 0, 0, $color == 0 ? imagecolorallocate($img, 0, 0, 0) : imagecolorallocate($img, 255, 255, 255));
	$font_bold = 'fonts/HelveticaNeueBold.ttf';
	$white = imagecolorallocate($img, 255, 255, 255);
	$black = imagecolorallocate($img, 0, 0, 0);	
	$y = 190;
	foreach(explode(' ', $setname) as $line) {
		imagettftext($img, 100, 0, 105, $y, ($color == 0 ? $white : $black), $font_bold, $line);
		$y += 140;
	}
	ob_start();
	imagepng($img);
	$result = ob_get_contents();
	ob_end_clean();
	imagedestroy($img);
	return $result;
	
}

function resizeImage($img, $ratio) {
	$out = imagecreatetruecolor(imagesx($img)/2, imagesy($img)/2);
	imagecopyresized($out, $img, 0, 0, 0, 0, imagesx($img)/2, imagesy($img)/2, imagesx($img), imagesy($img));
	return $out;
}

function generateCard($setName, $value, $type, $icon) {
	$bases = array('templates/png/wc_front.png', 'templates/png/bc_front.png', 'templates/png/bc_front_pick2.png', 'templates/png/bc_front_pick3.png');
	$img = imagecreatefrompng($bases[$type]);	
	$white = imagecolorallocate($img, 255, 255, 255);
	$black = imagecolorallocate($img, 0, 0, 0);
	
	$font = 'fonts/HelveticaNeue.ttf';
	$font_bold = 'fonts/HelveticaNeueBold.ttf';
	$wrapVal = wordwrap($value, 20, "\n");

	imagettftext($img, 49, 0, 80, 125, ($type == 0 ? $black : $white), $font_bold, $wrapVal);
	
	imagettftext($img, 18, 0, 190, 992, ($type == 0 ? $black : $white), $font_bold, $setName);
	$icon = imagerotate($icon, -22, imageColorAllocateAlpha($icon, 0, 0, 0, 127)); 
	imagealphablending($icon, true); 
	imagesavealpha($icon, true); 

	imagecopyresized($img, $icon, 131, 955, 0, 0, 41, 41, imagesx($icon), imagesy($icon));
	ob_start();
	imagepng($img);
	$result = ob_get_contents();
	ob_end_clean();
	imagedestroy($img);
	return $result;

}
?>