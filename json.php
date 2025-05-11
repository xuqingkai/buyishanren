<?php
    include_once('config.php');
    $html='';
    $text=file_get_contents('./xinyu.txt');
    $text=str_replace("\r","", trim($text));
    $text=str_replace("\n\n\n","`", $text);
    $html.='';
    foreach(explode('`',$text) as $category){
        $category_name=substr($category,0,strpos($category,"\n"));
        $html.=',"'.$category_name.'":';
        echo($category_name.'|');

        $articles=substr($category,strpos($category,"\n"));
        $articles=str_replace("\n\n","^", $articles);
        $html.='[';
        $temp='';
        foreach(explode('^',$articles) as $article){
            $title=substr(trim($article),0,strpos(trim($article),"\n"));
            $temp.=',{"title":"'.$title.'",';
            
            $contents=substr(trim($article),strpos(trim($article),"\n"));
            $contents=str_replace("\n","<br />", trim($contents));
            $temp.='"contents":"'.trim($contents).'"}';
/*
            $create_datetime=date('Y-m-d H:i:s');
            list($error,$data)=pdo_query("INSERT INTO article",array(
                'category'=>'心声',
                'chapter'=>$category_name,
                'title'=>$title,
                'contents'=>$contents,
                'create_datetime'=>$create_datetime
            ));
            if($error){exit($error);}
*/
        }
        if(strlen($temp)>0){ $html.=substr($temp,1); }
        $html.=']';
    }
    if(strlen($html)>0){ $html=substr($html,1); }
    $html='{'.$html.'}';
    //echo($html);
