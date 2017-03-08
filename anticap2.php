<?php

if(!isset($_GET['key']))

{

    $fs = fsockopen("market.yandex.ru", 80);                                                            //Connect to market.yandex.ru:80


    $head = "GET /captcha/captcha.xml?retpath=%2fsearch.xml%3ftext%3dhasbro HTTP/1.1\r\n";                //Build request

    $head .= "Host: market.yandex.ru\r\n";

    $head .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.4) Gecko/20091016 Firefox/3.5.4\r\n";


    $head .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";

    $head .= "Accept-Language: ru,en-us;q=0.7,en;q=0.3\r\n";

    $head .= "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7\r\n";

    $head .= "Keep-Alive: 300\r\n";


    $head .= "Connection: Close\r\n\r\n";

    fputs($fs, $head);                                                                                     //Send request

    $line = "";


    while(!feof($fs)) $line .= fgets($fs, 1024);                                                         //Get answer

    

    preg_match("/yandexuid=(\d+)/", $line, $matches);                                                    //Get yandexuid cookie variable


    $uid = $matches[1];



    preg_match("/name=\"key\" value=\"([^\"]+)\"/", $line, $matches);                                   //Get key


    $key = $matches[1];

    preg_match("|<img src=\"http://captcha\.yandex\.net/image\?key=[a-zA-Z0-9]+\">|", $line, $matches); //Get captcha image


    $img = $matches[0];

    

    echo $img."<br>";

    

    fclose($fs);


?>

<form action="captcha.php" method = "get">

<input type = "text" name = "code" value = "">


<input type = "hidden" name = "key" value = "<?php echo $key;?>">


<input type = "hidden" name = "uid" value = "<?php echo $uid;?>">


<input type = "submit" value = "Ok">

</form>

<?php

}

else

{

    $fs = fsockopen("market.yandex.ru", 80);


    $data = Array("key" => $_GET['key'], "response" => $_GET['code']);

    $head  = "GET /captcha/check-captcha.xml?retpath=/search.xml?text=hasbro&".http_build_query($data)." HTTP/1.1\r\n";


    $head .= "Host: market.yandex.ru\r\n";

    $head .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.4) Gecko/20091016 Firefox/3.5.4\r\n";


    $head .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";

    $head .= "Accept-Language: ru,en-us;q=0.7,en;q=0.3\r\n";

    $head .= "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7\r\n";

    $head .= "Connection: Close\r\n";


    $head .= "Cookie: yandexuid=".$_GET['uid']."\r\n\r\n";                            //Use our yandexuid

    fwrite($fs, $head);




    $result = "";

    while(!feof($fs))

        $result .= fgets($fs, 1024);




    if(!preg_match("/Yandex-CS-Captcha-Evidence=([^;]+);/", $result, $matches))        //Get magic string which will 

        Header("Location: captcha.php");                                            //make Yandex sure that it's


    $captcha = $matches[1];                                                            //not a robot :)



    $f = fopen("captcha", "w");                                                        //Save yandexuid and magic string


        fwrite($f, $captcha."\n");                                                    //for further use

        fwrite($f, $_GET['uid']."\n");


    fclose($f);



    echo "Ok";



    fclose($fs);

    

    //We should add the following header in our future requests:


    //Cookie: yandexuid=<yandexuid>; Yandex-CS-Captcha-Evidence=<magic_string>

}

?>
