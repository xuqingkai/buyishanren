<?php
include_once('config.php');
$id=intval($_GET['id']);
if(isset($_SERVER['HTTP_X_FC_REGION']) && isset($_SERVER['HTTP_X_FC_FUNCTION_NAME'])){
    $path='/home/oss/xuqingkai-data/buyishanren/audio/'.$id.'.mp3';
}else {
    $path='./audio/'.$id.'.mp3';
}

if(file_exists($path)){
    header('location:.'.substr($path,strpos($path,'/audio/')));exit();
    header('Content-Type:audio/mpeg');exit(file_get_contents($path));
}
list($error,$articles)=pdo_query('SELECT * FROM `article` WHERE id='.intval($_GET['id']), $param);
$article=$articles[0];
$text=$article['title']."\r\n\r\n".str_replace('<br />',"\r\n",$article['contents']);
$url='https://eastus.tts.speech.microsoft.com/cognitiveservices/v1';
$headers=array(
    'Ocp-Apim-Subscription-Key'=>'95f4633b32d24bdda1dfb2e4d43a2fdf',
    'Content-Type'=>'application/ssml+xml',
    'X-Microsoft-OutputFormat'=>'audio-16khz-128kbitrate-mono-mp3',
    'User-Agent'=>'curl',
);
$body='<speak version="1.0" xmlns="http://www.w3.org/2001/10/synthesis" xmlns:mstts="https://www.w3.org/2001/mstts" xml:lang="en-US">
<mstts:backgroundaudio src="string" volume="string" fadein="string" fadeout="string"/>
<voice xml:lang="zh-CN" xml:gender="Male" name="zh-CN-YunjianNeural">
<mstts:express-as style="poetry-reading" styledegree="1.1" role="OlderAdultMale">
<prosody rate="-30.00%">'.$text.'</prosody>
</mstts:express-as>
</voice>
</speak>';
//role="SeniorMale"
list($error, $response_body, $response_header)=http_file($url, $body, $headers);
file_put_contents($path,$response_body);
header('Content-Type:audio/mpeg');exit($response_body);
echo(json_encode($response_header));
function http_file($url, $body=false, $headers=array()){
    if(!$headers){
        $headers=array(
            'Content-Type:application/x-www-form-urlencode; charset=utf-8',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0',
            'Referer:'.substr($url,0,strpos($url,'/',10))
        );
    }else{
        foreach($headers as $key=>$val){
            $request_headers[]=is_numeric($key)?$val:$key.':'.$val;
        }
    }
    $request_headers=array_merge(array('Author:xuqingkai'),$request_headers);

    $error=false;
    $response_header=array();
    $response_body='';
    try{
        $response_body=file_get_contents($url, false, stream_context_create(array(
            'http'=>array(
                'ignore_errors'=>true,//即使有HTTP错误也忽略，强行获取内容
                'method'=>$body===false?'GET':'POST',
                'header'=>implode("\r\n",$request_headers),
                'content'=>$body===false?'':$body
            ),
            'ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false)
        )));
        $response_header=$http_response_header;
    }catch(Exception $ex){
        $error=$ex->getMessage();
    }
    return [$error, $response_body, $response_header];
}
function http_curl($url,$body=false,$headers=array()){
    if(!$headers){
        $headers=array(
            'Content-Type:application/x-www-form-urlencode; charset=utf-8',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0',
            'Referer:'.substr($url,0,strpos($url,'/',10))
        );
    }else{
        foreach($headers as $key=>$val){
            $request_headers[]=is_numeric($key)?$val:$key.':'.$val;
        }
    }
    $request_headers=array_merge(array('Author:xuqingkai'),$request_headers);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, true);//是否返回headers信息
    curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
    //curl_setopt($curl, CURLOPT_ENCODING,'gzip');
    if($body===false){
        curl_setopt($curl, CURLOPT_POST, false);
    }else{
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS , $body);
    }
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);//忽略重定向
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    $response_body=curl_exec($curl);
    $error = ($response_body === false?curl_error($curl):false);
    $header_size=curl_getinfo($curl,CURLINFO_HEADER_SIZE);
    $response_header=explode("\r\n",substr($response_body,0,$header_size));
    $response_body=substr($response_body,$header_size);
    curl_close($curl);
    return [$error, $response_body, $response_header];
}
?>