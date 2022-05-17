<?php
session_start();
require('dbconnect.php');

$page='';
$error['message']='';


if($_SESSION['id'] && $_SESSION['time'] + 3600 > time()){
    //ログインしている
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members where id=?');
    $members->execute([$_SESSION['id']]);
    $member = $members->fetch();
}else{//ログインしてない
    header('Location: login.php');exit();
}
//投稿の記録
if(!empty($_POST)){
    if($_POST['message'] != ''){
        $message = $db->prepare('INSERT INTO posts 
         SET member_id=?,message=?,reply_post_id=?,created=NOW()');
        $message->execute([$member['id'],$_POST['message'],$_POST['reply_post_id']]);
        header('Location:index.php');exit();
    }
}

//投稿を取得する
//isetないと$page定義されておらずエラー表示
if(!empty($_GET['page'])){
    $page= $_GET['page'];
    if($page == ''){
        $page=1;
    }
}

$page = max($page,1);

//最後のページを取得する
$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt']/5);
$page = min($page, $maxPage);

//最後のページのリンク
if(isset($_GET['lastPage'])){
    $page = $maxPage;
}

$start = ($page - 1) * 5;

$posts=$db->prepare('SELECT m.name,m.picture,p.* FROM members m,posts p
    WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');
$posts->bindParam(1,$start,PDO::PARAM_INT);
$posts->execute();
//返信の場合
if(isset($_GET['res'])){
    $response=$db->prepare('SELECT m.name,m.picture,p.* 
    FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
    $response->execute([$_GET['res']]);
    $table = $response->fetch();
    $message = '@'.$table['name'].' '.$table['message'];
}    
//htmlspecialcharsのショートカット
function h($value){
    return htmlspecialchars($value,ENT_QUOTES);
}
//本文内のURLにリンクを設定
function makeLink($value){
    return mb_ereg_replace("(https?)(://[[:alnum:]\+\$;\?\.%,!#~*/:@&=_-]+)",
    '<a href="\1\2">\1\2</a>',$value);
} 
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" type="text/css" href="style.css">
    <!--zoom.css-->
    <link rel="stylesheet" type="text/css" href="../zoom.css">

</head>

<body>
<main>
<div  style="text-align:right"><a class="menu" href="logout.php">ログアウト</a></div>

<form action="" method="post">
    <dl>
        <dt class="hello"><?php echo h($member['name']); ?>さん、メッセージをどうぞ</dt>
        <dd><textarea name="message" cols="80" rows="3" maxlength="102"
                onkeyup="ShowLength(value);"><?php 
                if(isset($message)){ echo h($message) ;}?></textarea>
            <p id="inputlength">0/102文字</p>
            <?php if(isset($_GET['res'])): ?>
                <input type="hidden" name="reply_post_id" maxlength="100"
                value="<?php echo h($_GET['res']);?>">
            <?php endif; ?>
        </dd>
    </dl>
    <div>
        <input class="submit" type="submit" value="投稿">
        <br><hr>
    </div>
</form>

<?php foreach($posts as $post): ?>
    <div class="msg">
        <div class="msg_box">
            <span id="expantion">
            <img class="icon" src="member_picture/<?php echo h($post['picture']); ?>"
            width="80" height="80" alt="<?php echo h($post['name']);?>" data-action="zoom">
            </span>
            <span class="name"><?php echo h($post['name']);?></span>
            <!--返信機能-->
            <a class="btn" href="index.php?res=<?php echo h($post['id']);?>">返信</a>
            <!--返信元-->
            <?php if($post['reply_post_id'] !== NULL): ?>
                <a class="btn" href="view.php?id=<?php echo h($post['reply_post_id']); ?>">返信元のメッセージ</a>
            <?php endif; ?>
            <!--メッセージ-->
            <span class="comment"><?php echo makeLink(h($post['message']));?>
            </span>
            <p class="day">
                <?php echo h($post['created']);?>    
                <?php  if($_SESSION['id']==$post['member_id']): ?>
                <a class="btn" href="delete.php?id=<?php  echo h($post['id']);?>" style="color:#F33;" onclick="return cfm()">削除</a>
                <?php  endif; ?>
                <a class="btn" href="view.php?id=<?php  echo h($post['id']); ?>">詳細</a>
            </p>
            <hr>
        </div>
    </div>
    
<?php endforeach; ?>
<br>
<div class="paging">
    <?php if($page > 2):  ?>
    <span><a class="pgbtn" href="index.php?page=1">≪</a></span>
    <?php endif; ?>
    <?php if($page > 1):  ?>
        <span><a class="pgbtn" href="index.php?page=<?php print($page -1); ?>"><?php print($page -1); ?></a></span>
    <?php endif; ?>
    <span class="pgbtn nowpage"><?php print($page); ?></span>
    <?php if($page < $maxPage): ?>
        <span><a class="pgbtn" href="index.php?page=<?php print($page + 1); ?>"><?php print($page + 1); ?></a></span>
    <?php endif; ?>
    <?php if($page < $maxPage-1): ?>
        <span><a class="pgbtn" href="index.php?lastPage">≫</a></span>
    <?php endif; ?>
</div>
</main>

<script>
    function cfm(){
    return confirm('本当に削除しますか');
}
</script>

<!--zoom.js-->
<script src="../../../jquery-3.6.0.min.js"></script>
<script src="../zoom.js" type="text/javascript"></script>
<!--expantion-->
<script type="text/javascript" src="./functions.js"></script>
</body>

</html>