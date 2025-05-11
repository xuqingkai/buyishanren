<?php include_once('config.php'); ?>
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
        body{background:url(./images/bg.png);}
        .uk-container{padding:0 3px}
        nav{background:#F0EFE2; opacity: 0.8;}
        .logo{padding:5px;font-size:1.5rem;font-weight:bold;background:#5D6146;padding-left:15px;}
        .logo a{color:#F0EFE2;}
        .search{text-align:center;padding-top:5px;}
        .uk-search{background:#fff; border:1px solid #5D6146;border-radius: 3px; overflow:hidden; width:99%;}
        .uk-navbar-item, .uk-navbar-nav>li>a, .uk-navbar-toggle{min-height:48px;}
        .uk-navbar-nav{ background:#888E6D;}
        .uk-navbar-nav>li>a{font-size:16px;color:#F0EFE2}
        .uk-navbar-nav>li.uk-active>a{border-bottom:3px solid #5D6146;color:#F0EFE2;font-weight:bold;}
        .sidebar{background:#F0EFE2;border:1px solid #d7d5bc;border-radius:5px;padding-right:15px;}
        .sidebar .uk-accordion-title{padding-left:15px;}
        .sidebar .uk-accordion-content{margin-top:0}
        .sidebar .uk-accordion-content a{font-size: 14px;float: left;padding:0 5px;margin-top: 10px;margin-left: 10px;border: 1px solid #DAD9D1;border-radius: 5px;}
        .uk-accordion{margin:10px 0;}
        .main{clear:both;}
        .main .uk-card{background:#F0EFE2;border:1px solid #d7d5bc;border-radius:5px;margin-bottom:20px;padding:20px;}
        .main .uk-card .autor{font-size:12px;color:#65645F;}
        .main .uk-card .autor img{height:25px; border-radius:50%;}
        .main .uk-card .autor a{color:#65645F;}
        .main .uk-card .tags img{height:20px}
        .main .uk-card .tags a{padding:0 5px;}
        .uk-card-title{color:#19537D; font-weight:bold; margin-bottom:15px;}
        .uk-card-title a{font-size:14px; color:#333; font-weight:normal;}
        .uk-card-contents{line-height:200%;color:#0F0F0F;padding-top:20px;}
        .page div{width:50%;text-align:center;background:#F0EFE2;margin-right:15px;}
        .page div a{display:block;padding:10px 0; }
        footer{margin:20px 0;padding:20px; text-align:center;background:#F0EFE2;}
	</style>
</head>
<body>
    <?php
    $sql='SELECT * FROM `article` WHERE category=:category';
    $param=array();
    if(strlen($_GET['category'])>0){
        $param['category']=$_GET['category']; 
    }else{
        $param['category']='心语'; 
    }
    if($_GET['chapter']){
        $sql.=' AND chapter=:chapter';
        $param['chapter']=$_GET['chapter']; 
    }
    if(strlen($_GET['search'])>0){
        $sql.=' AND ((title LIKE :title) OR (contents LIKE :contents))';
        $param['title']='%'.$_GET['search'].'%';
        $param['contents']='%'.$_GET['search'].'%';
    }
    $limit=10;
    $page=max(intval('0'.$_GET['page']),1);
    if($page>1){ $limit=$limit.','.($limit*($page-1));  }
    $sql.=' LIMIT '.$limit;
    list($error,$articles)=pdo_query($sql, $param);
    ?>
    <header>
        <div class="uk-container1">
            <div class="logo"><a href="./">布衣山人</a></div>
        </div>
        <div class="search">
            <div class="uk-container1">
                <form class="uk-search uk-search-default">
                    <input type="hidden" name="category" value="<?php echo($_GET['category']); ?>" />
                    <input class="uk-search-input" type="search" placeholder="" name="search">
                    <span class="uk-search-icon-flip" uk-search-icon></span>
                </form>
            </div>
        </div>
        <nav>
            <div class="uk-container1">
                <ul class="uk-navbar-nav uk-child-width-expand" id="category">
                    <li<?php echo(($_GET['category']=='心语' || $_GET['category']=='')?' class="uk-active"':''); ?>><a href="?category=心语">心语</a></li>
                    <li<?php echo($_GET['category']=='心声'?' class="uk-active"':''); ?>><a href="?category=心声">心声</a></li>
                    <li<?php echo($_GET['category']=='其他'?' class="uk-active"':''); ?>><a href="?category=其他">其他</a></li>
                    <li<?php echo($_GET['category']=='作者'?' class="uk-active"':''); ?>><a href="?category=作者">作者</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="uk-container">
            <div class="sidebar">
                <ul uk-accordion="multiple: true">
                    <li>
                        <div class="uk-accordion-title">分类标签</div>
                        <div class="uk-accordion-content">
                            <?php
                            $sql='SELECT chapter FROM `article` WHERE category=:category';
                            $param=array();
                            if(strlen($_GET['category'])>0){
                                $param['category']=$_GET['category']; 
                            }else{
                                $param['category']='心语'; 
                            }
                            list($error,$rows)=pdo_query($sql." GROUP BY chapter ORDER BY id ASC", $param);
                            //$chapters='三言|四言|五绝|五言|六言|七绝|七言|西江月|清平乐|虞美人|临江仙|蝶恋花|长相思|望秦川|卜算子|水调歌头|念奴娇|双调|贺新郎|满江红|沁园春|浪淘沙|唐多令|黄莺儿|忆秦娥|渔家傲|满庭芳|洞仙歌|采桑子|一剪梅|玉蝴蝶|南柯子|江南春|踏沙行|水龙吟|踏月|南乡子|天净沙|高阳台|辞赋|新诗';
                            foreach($rows as $row){
                                echo('<a href="?category='.$_GET['category'].'&chapter='.$row['chapter'].'">'.$row['chapter'].'</a>');
                            }
                            ?>
                            <div style="clear:both"></div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="main">
                <?php foreach($articles as $article){ ?>
                <div class="uk-card uk-card-body">
                    <h3 class="uk-card-title"><?php echo($article['title']); ?> <a href="">( <?php echo($article['chapter']); ?> )</a></h3>
                    <div class="autor">
                        <img src="./images/author.jpg" />&nbsp;&nbsp;&nbsp;布衣山人 
                    </div>
                    <div class="uk-card-contents"><?php echo($article['contents']); ?></div>
                    <hr class="uk-divider-icon">
                    <div class="tags">
                        <audio controls preload="none" style="width:100%;"><source src="./voice.php?id=<?php echo($article['id']); ?>" type="audio/mpeg">您的浏览器不支持 audio 元素。</audio>
                    </div>
                </div>
                <?php } ?>
                <?php if($page>0 && count($articles)>0){ ?>
                <div class="uk-flex page">
                    <div><a href="<?php echo($page>1?'?category='.$_GET['category'].'&chapter='.$_GET['chapter'].'&page='.($page-1):'javascript:void(0);'); ?>">上一页</a></div>
                    <div><a href="javascript:void(0);"><?php echo($page); ?></a></div>
                    <div style="margin-right:0"><a href="<?php echo('?category='.$_GET['category'].'&chapter='.$_GET['chapter'].'&page='.($page+1)); ?>">下一页</a></div>
                </div>
                <?php } ?>
            </div>
    </div>
    <div class="uk-container">
        <footer>
        Copyright &copy; 2005 - <?php echo(date('Y')); ?> xuqingkai.com All rights reserved.
        </footer>
    </div>
</body>
</html>