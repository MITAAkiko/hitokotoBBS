<?php
require('dbconnect.php');
session_start();
$_COOKIE['email']='';

if($_COOKIE['email'] != ''){
    $_POST['email']=$_COOKIE['email'];
    $_POST['password']=$_COOKIE['password'];
    $_POST['save'] = 'on';
}

if(!empty($_POST)){
    //ログインの処理
    if($_POST['email'] !='' && $_POST['password'] != ''){
        $login = $db->prepare('select * from members WHERE email=? AND password=?');
        $login->execute([
            $_POST['email'],
            sha1($_POST['password'])
        ]);

        $member=$login->fetch();
        if($member){
            //ログイン成功
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
                //ログイン情報を記録する   
                if($_POST['save']=='on'){
                    setcookie('email',$_POST['email'],time()+60*60*24*14);
                    setcookie('password',$_POST['password'],time()+60*60*24*14);
                }
           header('Location:index.php');
           exit();
        }else{
            $error['login'] = 'failed';
        }
    }else{
        $error['login'] = 'blank';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="./style.css">
</head>
<main>
<div class="login_content">

<div id="lead">
    <h3 class="login_title">ログイン</h3>
    <p class="sudeni">入会手続きがまだの方　<span><a class="join" href="join/">新規入会</a></span></p>
    
</div>
<form acrion="" method="post">
    <dl>
        <dt>メールアドレス</dt>
        <dd>
            <input type="text" name="email" size="35" maxlength="255"
             value="<?php if(isset($_POST['email'])){
             echo htmlspecialchars($_POST['email'],ENT_QUOTES);} ?>">
        </dd>
        <dt>パスワード</dt>
        <dd>
            <input type="password" name="password" size="35" maxlength="255"
             value="<?php if(isset($_POST['password'])){
              echo htmlspecialchars($_POST['password'],ENT_QUOTES); }?>">
        </dd>
        <?php if (isset($error['login']) && $error['login'] == 'blank'): ?>
                <p class="error">※メールアドレスとパスワードをご記入ください</p>
        <?php endif; ?>
        <?php if (isset($error['login']) && $error['login'] == 'failed'): ?>
                <p class="error">※ログインに失敗しました。正しくご記入ください。</p>
        <?php endif; ?>
        <dt>ログイン情報の記録</dt>
        <dd>
            <input class="hover" id="save" type="checkbox" name="save" value="on">
            <label class="hover" for="save">次回からは自動ログインする</label>
        </dd>
    </dl>
    <div><input class="login_btn" type="submit" value="ログイン"></div>
</form>

</div>
</main>