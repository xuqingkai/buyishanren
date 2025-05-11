<?php
if(strpos($_SERVER['HTTP_USER_AGENT'],'Android')!==false || strpos($_SERVER['HTTP_USER_AGENT'],'iOS')!==false){
    require_once('./m.php');
}else{
    require_once('./pc.php');
}
?>