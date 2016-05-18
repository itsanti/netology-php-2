<?php

error_reporting(E_ALL);

$name = '%username%';

if (!empty($_GET['name'])) {
    $name = base64_decode($_GET['name']);
}

$font = realpath(__DIR__ . '/fonts/DejaVuSans.ttf');
$fontName = realpath(__DIR__ . '/fonts/CaslonRoman.ttf');
$sert = imagecreate(800, 600);
$bg   = imagecolorallocate($sert, 52, 173, 227);

// ellipse
$el = imagecreate(800, 600);
$bg = imagecolorallocate($el, 52, 173, 227);
$elColor = imagecolorallocate($el, 185, 212, 15);
imagefilledellipse($el, 400, 300, 800, 600, $elColor);
$elRotate = imagerotate($el, -45, $bg);
imagecopymerge($sert, $elRotate, 0, 0, 450, 350, 400, 500, 80);
imagedestroy($el);
imagedestroy($elRotate);
// circle
$circleColor = imagecolorallocate($sert, 76, 167, 4);
imagefilledellipse($sert, 700, 400, 500, 500, $circleColor);
// text
$text = imagecreate(700, 500);
$w = imagecolorallocate($text, 255, 255, 255);
$bg = imagecolorallocate($text, 122, 122, 122);
imagefill($text, 0, 0, $w);
$textColorB = imagecolorallocate($text, 0, 173, 239);
imagettftext($text, 60, 0, 60, 120, $textColorB, $font, "СЕРТИФИКАТ");
$textColorG = imagecolorallocate($text, 120, 120, 120);
$t = "Настоящим сертификатом\nудостоверяется, что";
imagettftext($text, 14, 0, 60, 180, $textColorG, $font, $t);
imagettftext($text, 40, 0, 60, 270, $textColorB, $fontName, $name);
$t = "Прошел тест\nи получил оценку";
imagettftext($text, 14, 0, 60, 350, $textColorG, $font, $t);
imagerectangle($text, 300, 300, 400, 400, $bg);
$textColorR = imagecolorallocate($text, 120, 120, 0);
imagettftext($text, 100, 0, 325, 390, $textColorR, $fontName, '5');
$sjpeg = imagecreatefromjpeg(realpath(__DIR__ . '/img/certified.jpg'));
imagecopy($text, $sjpeg, 430, 280, 0, 0, 250, 200);
imagedestroy($sjpeg);
// total
imagecopy($sert, $text, 50, 50, 0, 0, 700, 500);
imagedestroy($text);

header( "Content-type: image/png" );
imagepng($sert);
imagedestroy($sert);
