#!/usr/bin/php
<?php
$lang = $_SERVER['argv'][1];
$dir = $lang.date ('/Y/m/');
if(!is_dir($dir)){
    mkdir($dir,0777,true);
}
$url = "http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt={$lang}";
$data = json_decode(file_get_contents($url),true);
$images = $data['images'][0];
$imgurl = 'http://www.bing.com'.$images['url'];
$file = $images['fullstartdate'];
$name = strstr(substr($images['urlbase'],11),'_',true);
$file .= $name;
$file .= '.jpg';
$path = $dir.$file;
$md_path = $dir."README.md";
if(!is_file($path)){
    $imgdata = file_get_contents($imgurl);
    file_put_contents($path,$imgdata);
//     file_put_contents("{$lang}.jpg",$imgdata);
    shell_exec ("/opt/mozjpeg/bin/cjpeg -quality 80 {$path} > {$lang}.jpg");
    file_get_contents('https://cdn.jsdelivr.net/gh/aburrido/bing@latest/'.{$lang}.'.jpg');
    if(!is_file($md_path)){
        file_put_contents($md_path,"|fullstartdate|name|copyright|title|image|\n|--|--|--|--|--|\n");
    }
    file_put_contents($md_path,$images['fullstartdate']."|".$name."|".$images['copyright']."|".$images['title']."|"."![](/{$path})"."|\n",FILE_APPEND);
}
