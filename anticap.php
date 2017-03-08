<?php
//echo '<form class="form__inner" method="get" action="http://yandex.ru/checkcaptcha">';
//$img_cap = $html->find('img[class=form__captcha]');
//echo '<img src='.$img_cap[0]->src.'><br>';

///$pp = $html->find('h1, a, img, br,span');//первый попавшийся элемент
//echo $pp[6].' <span>строка ввода капчи</span></br>';

//echo ' <button class="button button_size_m button_side_right button_theme_normal form__submit i-bem" role="button" type="submit" data-bem="{&quot;button&quot;:{}}">
//					<span class="button__text">Отправить</span>
//				</button></br>';
//echo '</form>';
session_start();
$rep = $_POST['rep'];
echo '<p>это составная капча</p>';
echo '<p>http://yandex.ru/checkcaptcha?key='.$_SESSION['keyca'].'&rep='.$rep.'&retpath='.$_SESSION['retpath'].'</p>';

/*$curl = curl_init();
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_COOKIEJAR, $HOMEDIR.'/cookie/cookies_yandex.txt');
curl_setopt($curl, CURLOPT_COOKIEFILE, $HOMEDIR.'/cookie/cookies_yandex.txt');

curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36');


curl_setopt($curl, CURLOPT_URL, 'http://yandex.ru/checkcaptcha?key='.$_SESSION['keyca'].'&rep='.$rep.'&retpath='.$_SESSION['retpath']);

$html = curl_exec($curl);
echo '<p>попытка 2</p>';
echo $html;*/

$host = "yandex.ru"; 
 
 
$Referer = '';
$User_Agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)';
$Accept = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
$Accept_Language = 'ru-ru,ru;q=0.8,en-us;q=0.5,en;q=0.3';
$Accept_Charset = 'windows-1251,utf-8;q=0.7,*;q=0.7';
$Content_Type = 'Content-Type: text/html; charset=iso-8859-1';
$Cookie = 'guid=A0F20A054CDB1503X1289426179';
 
 
$samo = fsockopen($host,80);
$vars = '';
    $request = '';
    $request .= "GET {$uri} HTTP/1.1\r\n";
    $request .= "Host: {$host}\r\n";
    $request .= "User-Agent: {$User_Agent}\r\n";
    $request .= "Accept: {$Accept}\r\n";
    $request .= "Accept-Language: {$Accept_Language}\r\n";
    $request .= "Accept-Charset: {$Accept_Charset}\r\n";
    $request .= "Referer: {$Referer}\r\n";
    $request .= "Cookie: {$Cookie}\r\n";
    $request .= "Content-Type: {$Content_Type}\r\n";
    $request .= "Keep-Alive: 115\r\n";
    $request .= "Accept-Encoding: deflate\r\n";
    $request .= "Connection: close\r\n\r\n";
  //$request .= "Connection: keep-alive\r\n\r\n";
 
$body = '';
fputs($samo, $request);                                    
while(!feof($samo)) $body .= fgets($samo);//считываю полученное в переменную
fclose($samo);
echo $body;

?>