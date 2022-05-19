<?php session_start();
require('../dbconnect.php');


if($_SESSION['id'] && $_SESSION['time'] + 3600 > time()){
    //ログインしている
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members where id=?');
    $members->execute([$_SESSION['id']]);
    $member = $members->fetch();
}else{//ログインしてない
    header('Location: login.php');exit();
}

if(isset($_GET['mypage'])){
    $myPages = $db -> prepare('SELECT * FROM members where id=?');
    $myPages -> execute([$_GET['mypage']]);
    $myPage = $myPages->fetch();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="../style.css">
</head>

<body>
<main>
<div class="join_content">
<h3 class="login_title">マイページ</h3>

<p><a class="menu" href="../index.php">一覧にもどる</a></p>
<!--getにIDが格納されたので、ここからDBとつなげる-->
<div class="hello"><?php echo htmlspecialchars($myPage['name'],ENT_QUOTES); ?>さん、メッセージをどうぞ</div>

<!--
    <img class="icon" src="../member_picture/<?php // echo h($_SESSION['picture']); ?>"
            width="80" height="80" alt="<?php // echo h($_SESSION['name']);?>">
-->
<!-- 
<form action="" method="post">
    <input type="hidden" name="action" value="submit">
    <dl>
        <dt>ニックネーム</dt>
        <dd>
            <?php // echo htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES);?>
        </dd>
        <dt>メールアドレス</dt>
        <dd>
            <?php // echo htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES);?>
        </dd>
        <dt>パスワード</dt>
        <dd>【表示されません】</dd>
        <dt>写真など</dt>
        <dd>
            <img src="../member_picture/<?php // echo htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES);?>" 
            width="100" height="100" alt="">
        </dd>
    </dl>
    <div><a class="rewrite" href="index.php?action=rewrite">書き直す</a>
    <input class="checked green_btn" type="submit" value="登録"></div>
</form>
-->
</div>
</main>
</body>