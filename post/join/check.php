<?php session_start();
require('../dbconnect.php');

//データ登録せず、リンクに直接アクセスした人を戻す
if(!isset($_SESSION['join'])){
    header('Location:index.php');
    exit();
}
//セッションの値をDBに保存
if(!empty($_POST)){
    $statement = $db->prepare('INSERT INTO members 
    SET name=?,email=?,password=?,picture=?,created=NOW()');
    echo $ret=$statement->execute([
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ]);
    unset($_SESSION['join']);

    header('Location:thanks.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="./stylejoin.css">
<link rel="stylesheet" href="../style.css">
</head>

<body>
<main>
<div class="join_content">
<h3 class="join_title">登録情報確認</h3>

<form action="" method="post">
    <input type="hidden" name="action" value="submit">
    <dl>
        <dt>ニックネーム</dt>
        <dd>
            <?php echo htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES);?>
        </dd>
        <dt>メールアドレス</dt>
        <dd>
            <?php echo htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES);?>
        </dd>
        <dt>パスワード</dt>
        <dd>【表示されません】</dd>
        <dt>写真など</dt>
        <dd>
            <img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES);?>" 
            width="100" height="100" alt="">
        </dd>
    </dl>
    <div><a class="rewrite" href="index.php?action=rewrite">書き直す</a>
    <input class="checked green_btn" type="submit" value="登録"></div>
</form>

</div>
</main>
</body>