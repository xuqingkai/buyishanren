<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>布衣山人</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/icon" />
    <link href="./css/uikit.min.css" rel="stylesheet" />
    <script src="./js/uikit.min.js" type="text/javascript"></script>
    <script src="./js/uikit-icons.min.js" type="text/javascript"></script>
    <style type="text/css">
		.uk-card {transition: all .3s cubic-bezier(.2, .5, .3, 1);}
		.uk-card:hover {transform: translateY(-5px);}
	</style>
</head>
<body>
<div class="uk-container uk-container-xsmall">
<?php
    $html='';
    $text=file_get_contents('./xinyu.txt');
    $text=str_replace("\r","", trim($text));
    $text=str_replace("\n\n\n","`", $text);
    $html.='<ul uk-accordion="animation:false">'."\r\n";
    
    foreach(explode('`',$text) as $category){
        $category_name=substr($category,0,strpos($category,"\n"));
        $html.='    <li>'."\r\n";
        $html.='        <h1 class="uk-accordion-title"><strong>'.$category_name.'</strong></h1>'."\r\n";

        $articles=substr($category,strpos($category,"\n"));
        $articles=str_replace("\n\n","^", $articles);
        $html.='        <div class="uk-accordion-content">'."\r\n";
        foreach(explode('^',$articles) as $article){
            $title=substr(trim($article),0,strpos(trim($article),"\n"));
            $html.='            <div class="uk-card uk-card-default uk-card-body">'."\r\n";
            $html.='                <h3 class="uk-card-title"><strong><a name="#'.$category_name.'/'.$title.'">'.$title.'</a></strong></h3>'."\r\n";
            
            $contents=substr(trim($article),strpos(trim($article),"\n"));
            $contents=str_replace("\n","<br />", trim($contents));
            $html.='                <p>'.$contents.'</p>'."\r\n";
            $html.='            </div>'."\r\n";
        }
        $html.='        </div>'."\r\n";
        $html.='    </li>'."\r\n";
    }
    $html.='</ul>';
    echo($html);
?>
</div>
</body>
</html>