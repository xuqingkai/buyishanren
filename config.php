<?php
error_reporting(0);
date_default_timezone_set('PRC');
function pdo_query($sql,$param=false,$where=false){
    $pdo=new PDO('sqlite:./data.db');
    //$pdo->exec("SET NAMES utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $error=false; $data=false;
    try{
        if($param!==false){//参数模式
            if(substr(strtoUpper($sql),0,7)=='SELECT '){
                if($where){
                    $sql.=(strpos($sql, ' WHERE ')===false?' WHERE ':' AND ').$where;
                }
            }elseif(substr(strtoUpper($sql),0,11)=='INSERT INTO'){
                $fields='';$values='';
                foreach($param as $key=>$val){
                    $fields.=strlen($fields)>0?','.$key:$key;
                    $values.=strlen($values)>0?',:'.$key:':'.$key;
                }
                if(strpos($sql, ' ) VALUES ( ')===false){
                    $sql.=' ('.$fields.') VALUES ('.$values.')'; 
                }
            }elseif(substr(strtoUpper($sql),0,7)=='UPDATE '){
                if($where){
                    $set='';
                    foreach($param as $key=>$val){ $set.=(strlen($set)>0?',':'').$key.'=:'.$key; }
                    $sql.=(strpos($sql, ' SET ')===false?' SET ':',').$set;
                    $sql.=(strpos($sql, ' WHERE ')===false?' WHERE ':' AND ').$where;
                }
            }elseif(substr(strtoUpper($sql),0,7)=='DELETE '){
                if($where){
                    $sql.=(strpos($sql, ' WHERE ')===false?' WHERE ':' AND ').$where; 
                }
            }

            
            if(substr(strtoUpper($sql),0,11)=='INSERT INTO' && strpos($sql, ' ) VALUES ( ')===false){
                $fields='';$values='';
                foreach($param as $key=>$val){
                    $fields.=strlen($fields)>0?','.$key:$key;
                    $values.=strlen($values)>0?',:'.$key:':'.$key;
                }
                $sql.=' ('.$fields.') VALUES ('.$values.')'; 
            }
            //exit($sql);
            $prepare=$pdo->prepare($sql);
            $prepare->execute($param);
            if(substr(strtoUpper($sql),0,7)=='SELECT '){//返回查询数据
                $data=$prepare->fetchAll(PDO::FETCH_ASSOC);
            }else{
                if(substr(strtoUpper($sql),0,11)=='INSERT INTO'){//返回最后插入ID
                    $data=$pdo->lastInsertId();
                }else{//返回受影响行数
                    $data=$prepare->rowCount();
                }
            }
        }else{//纯语句模式
            if(substr(strtoUpper($sql),0,7)=='SELECT '){//返回查询数据
                $query=$pdo->query($sql);
                $data=$query->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $data=$pdo->exec($sql);
                if(substr(strtoUpper($sql),0,11)=='INSERT INTO'){//返回最后插入ID
                    $data=$pdo->lastInsertId();
                }
            }   
        }
    }catch(PDOException $ex){
        $error=$ex->getMessage();
    }
    return [$error,$data];
}