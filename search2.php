<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Ответ</title>
</head>
<body>
<a href="index.php"><< назад</a>
<?php

//подключили библиотеку
require_once 'simple_html_dom.php';
$query = $_POST['query']; 
echo '<p>ваш запрос: <b>'.$query.'</b></p>';
//скачали страничку
$html = file_get_html('https://www.yandex.ru/search/?text='.$query.'&lr=50');

$url = 'http://yandex.ru/search/?text='.$query.'&lr=50';


function get_webpage($url,$cookie){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2) Gecko/20100115 Firefox/3.6');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, '10000');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, FALSE);
	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
	$data = curl_exec($ch);
	if ($data)
		return $data;
	else
		return 'CURL failed';
}

function get_serp_page($url){//ПОЛУЧАЕМ ТЕКСТ И ПРОВЕРЯЕМ ЕСТЬ ЛИ КАПТЧА
	$page = get_webpage($url);
	preg_match('|<img src="(.*)" class="b-captcha__image">|isU', $page, $matches);
	if(isset($matches[1])){
		$page = break_captcha($page,$matches[1]);
	}
	return $page;
}

function break_captcha($page,$src_img){//АНАЛИЗИРУЕМ КАПТЧУ
	preg_match_all("#spravka=(.*?);#", $page, $t); $cookie = $t[1]; 
	preg_match('|name="key" value="(.*)">|isU', $page, $t); $key = $t[1]; 
	preg_match('|name="retpath" value="(.*)">|isU', $page, $t); $retpath = $t[1];
	$captcha = file_get_contents($src_img);
	file_put_contents('ya_cap.gif', $captcha);
	$text=recognize("ya_cap.gif","00ceb718ed1e1fe411cba04b2fbc4568",false,"http://antigate.com/in.php");
	$m1=array('%2A','%26amp%3B');$m2=array('*','%26');
	$yurl = 'http://yandex.ru/checkcaptcha?key='.urlencode($key).'&retpath='.str_replace($m1,$m2,urlencode($retpath)).'&rep='.rawurlencode($text).'';
	$page = get_webpage($yurl,$cookie);
	if (stripos($page, 'введите цифры с картинки') > 0){
		echo "<br>Снова капча :-( <br>";
		exit();
	}
	return $page;
}

$page = get_webpage($url);
$page = break_captcha($page,$matches[1]);

//собираем ссылки
while($i!=$p){ 
$a_links = $html->find('a[class=organic__url]');
//$query_bd=('INSERT INTO urls VALUES ('$a_links')');

$a_l = $a_links[ $i ];
//$insert_sql = "INSERT INTO urls (a_links)" ."VALUES('{$a_l}');";
//mysql_query($insert_sql);
echo $a_l->href.'<br>';
$i++;
} 

$html->clear(); 
unset($html);  
?>


</body>
</html>
