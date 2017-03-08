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
$queryy = str_replace (' ','%20', $_GET[$query]);// заменяем обычный пробел на pinycode
//скачали страничку
$html = file_get_html('https://www.yandex.ru/search/?text='.$queryy.'&lr=50');


$p = 10; 
$i=0;

if (count($html->find('img[class=form__captcha]')))// ищем капчу. Если находим, то разгадываем
{
	//форма с вводом капчи
echo '<form class="form__inner" method="post" action="/anticap.php" enctype="multipart/form-data">';
				
$img_cap = $html->find('img[class=form__captcha]');//ищем картинку с капчей
echo '<img src='.$img_cap[0]->src.'><br>';//показываем её

$keycap = $html->find('input[class="form__key"]');//ищем уникальный ключ, который нужно будет отдать с разгаданной капчей
$keycaptcha = urlencode($keycap[0]->value);//кодируем код, чтобы не потерялся по дороге
$_SESSION['keyca']=$keycaptcha;//отправляем 

$retp = $html->find('input[class="form__retpath"]');//что-то типа идентификатора сессии, его тоже нужно отправить с разгаданной капчей
$retpath = html_entity_decode($retp[0]->value);
$retpath = urlencode($retpath);
$_SESSION['retpath']=$retpath;

echo '<input class="input__control" id="rep" name="rep" placeholder="разгадай капчу" aria-labelledby="labeluniq14851765582481 hintuniq14851765582481" autocorrect="off" spellcheck="false" autocomplete="off">';
			
echo ' <button class="button button_size_m button_side_right button_theme_normal form__submit i-bem" role="button" type="submit" data-bem="{&quot;button&quot;:{}}">
		<span class="button__text">Отправить</span>
		</button></br>';
echo '</form><br>';



//если нужно будет прикрутить антикапчу
/*echo '<form method="post" action="http://antigate.com/in.php" enctype="multipart/form-data">';
echo '<input type="hidden" name="HTTP POST with multipart/form-data encoding" value="post"><br>';
//   Ключ вашего аккаунта:
echo '      <input type="text" name="key" value="00ceb718ed1e1fe411cba04b2fbc4568" size="32"><br>';
//   Файл капчи:
echo '      <input type="file" name="file"><br>';
echo '   <input type="submit" value="загрузить и получить ID"><br>';
echo '</form>';*/
}

else//если капчи нет, то собираем ссылки
{

while($i!=$p){ 
$a_links = $html->find('a[class=organic__url]');

$a_l = $a_links[ $i ];
echo $a_l->href.'<br>';
$i++;
} 
}

$html->clear(); 
unset($html);  
?>


 
<hr>
<a href="index.php"><< назад</a>
<!--?php

//подключили библиотеку
require_once 'simple_html_dom.php';

$query = $_POST['query']; 

$arr = explode( "\n", $query);//разбиваем текст на строки
print_r($arr); //выводим массив запросов
$result = count($arr);// считаем количество запросов
echo $result;


for ($j=0; $j <$result; $j++){
	echo '<p>'.$arr[$j].'</p>';
	$arr[$j] = str_replace (' ','%20', $_GET[$arr[$j]]);// заменяем обычный пробел на pinycode

//echo '<p>ваш запрос: <b>'.$query.'</b></p>';
//скачали страничку
$html = file_get_html('https://www.yandex.ru/search/?text='.$arr[$j].'&lr=50');


//ищем картинку с капчей

$img_cap = $html->find('img');
echo '<img src='.$img_cap[j]->src.'><br>';

//ищем заголовок аш1
$hh1 = $html->find('h1');
echo $hh1.'<br>';

$p = 10; 
$i=0;
//собираем ссылки со страницы
	while($i!=$p){ 
		$a_links = $html->find('a[class=organic__url]');
		$a_l = $a_links[ $i ];
		echo $a_l->href.'<br>';
		$i++;
	} 

$html->clear(); 
unset($html);  
}
?--> 

</body>
</html>
