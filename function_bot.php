<?php

$fileName = 'functionlist.txt';
$url = '';
$message = '';


// 関数リストの読み込み
$functionList = file($fileName);
// 関数リストをシャッフル
if(shuffle($functionList)){
    //関数リストの先頭を改行抜きで取り出す
    $functionName = rtrim($functionList[0]);
    // 「_」を「-」に変換
    $functionName = str_replace("_", "-", $functionName);
    // php.netのurlを生成
    $url = 'http://php.net/manual/ja/function.'. $functionName .'.php';
}


// HTMLの取得
if($html = file_get_contents($url)){

    // HTMLのパース
    // 関数名取得
    preg_match('/<h1 class="refname">(.*)<\/h1>/', $html, $match);
    $refname = $match[1];

    // 関数説明取得
    preg_match('/<span class="dc-title">(.*?)<\/span>/s', $html, $match);
    $title = $match[1];

    // 関数使い方取得
    preg_match('/<div class="methodsynopsis dc-description">(.*?)<\/div>/s', $html, $match);
    // 改行文字とタグを除去
    $description = str_replace(array("\r\n","\n","\r"), '', strip_tags($match[1]));

    // 関数説明の語尾にぞうをつけるぞう
    if(preg_match("/.*る$/", $title) ||
       preg_match("/.*く$/", $title) ||
       preg_match("/.*う$/", $title) ||
       preg_match("/.*す$/", $title)){

        $title .= "ぞう";

    }else{

        $title .= "するぞう";

    }

    // 投稿メッセージの生成
    $message = $refname. "\n". $title. "\n". $description;

    //投稿メッセージが140文字を超えてしまう場合は、関数の使い方は省く
    if(mb_strlen($message) > 140){
        $message = $refname. "\n". $title. "\n". "使い方はググってほしいぞう";
    }

}else{
    // HTMLの取得に失敗した場合はなんか言う
    $errorMessage = ['ぱお〜〜ん！！', 'ぞうにはないぞうがないぞうだなんていわせないぞう',
    '今日はちょっと疲れたぞう'];

    shuffle($errorMessage);
    $message = $errorMessage[0];
}

echo $message;


// OAuthライブラリの読み込み
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

// Consumer key
$consumerKey = "";
// Consumer secret
$consumerSecret = "";
// Access token
$accessToken = "";
// Access token secret
$accessTokenSecret = "";


// tweetする
$connection = new TwitterOAuth($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret);
$res = $connection->post("statuses/update",array("status"=> $message ));

// レスポンス確認
var_dump($res);


