<?php
require('../dbconnect.php');

session_start();
//isset いらなくなる
//empty($error)を!isError
function isError($err){
    $nonerror=[
        'name' => '',
        'email' => '',
        'password' => '',
        'image' => ''
    ];
    return $err !== $nonerror;
}

//初期値
$error = [
    'name' => '',
    'email' => '',
    'password' => '',
    'image' => ''
];
$isError='';


if(!empty($_POST)){
    if($_POST['name']===''){
        $error['name']='blank';
    }
    if(strlen($_POST['name'])>32){
        $error['name']='long';
    }
    if($_POST['email']===''){
        $error['email']='blank';
    }
    if(strlen($_POST['password']) < 4){
        $error['password']='length';
    }
    if ($_POST['password'] === '') {
		$error['password'] = 'blank';
	}
    $fileName=$_FILES['image']['name'];
    if(!empty($fileName)){
        $ext = substr($fileName,-3);
        if($ext !== 'jpg' && $ext !== 'git' && $ext !== 'png'){
            $error['image'] = 'type';
        }
    }

    //エラーがある.ファンクションそのまま使えないから変数に代入
    $isError = isError($error);


    //重複アカウントのチェック
    if(!$isError){
        $member = $db->prepare('SELECT COUNT(*) AS cnt from members WHERE email=?');
        $member->execute([$_POST['email']]);
        $record = $member->fetch();
        if($record['cnt']>0){
            $error['email']='duplicate';
        }
    }

    if(!$isError){
        //画像を指定しなかったときの代わりの画像
        if(empty($fileName)){
            $image = 'noimage.png';
        }else{
            $image = date('YmdHis').$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/'.$image);
        }
        $_SESSION['join']=$_POST;
        $_SESSION['join']['image']=$image;
        header('Location:check.php');
        exit();
    }
}
//書き直し
if(isset($_REQUEST['action']) && $_REQUEST['action']==='rewrite'){
    $_POST = $_SESSION['join'];
    $isError = true;
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    
    <title>ひとこと掲示板</title>
    <link rel="stylesheet" href="./stylejoin.css">
    <link rel="stylesheet" href="../style.css">

</head>

<body>

<main>

<div class="join_content">
<h3 class="join_title">入会登録</h3>

<p>次のフォームに必要事項をご記入ください</p>
<p class="sudeni">既にアカウントをお持ちの方はこちら <a class="join_login_btn" href="../">ログイン
</a><p>
<form action="" method="post" enctype="multipart/form-data">
    <dl>
    <dt>ニックネーム<span class="required">必須</span></dt>
    <dd>
        <input type="text" name="name" size="35" maxlength="32"
         value="<?php  if (isset($_POST['name'])){
            echo htmlspecialchars($_POST['name'],ENT_QUOTES);}?>">
        <?php if ($error['name']=='blank'): ?>
        <p class="error">※ニックネームを入力してください</p>
        <?php endif; ?>
        <?php if ($error['name']=='long'): ?>
        <p class="error">※ニックネームは32文字以内で入力してください</p>
        <?php endif; ?>
    </dd>
    <dt>メールアドレス<span class="required">必須</span></dt>
    <dd>
        <input type="text" name="email" size="35" maxlength="255" 
         value="<?php if (isset($_POST['email'])){
            echo htmlspecialchars($_POST['email'],ENT_QUOTES);}?>">
        <?php if($error['email']==='blank'): ?>
        <p class="error">※メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if ($error['email']==='duplicate'): ?>
            <p class="error">※指定されたメールアドレスは既に登録されています</p>
            <?php endif; ?>
    </dd>
    <dt>パスワード<span class="required">必須</span></dt>
    <dd>
        <input type="password" name="password" size="10" maxlength="20" 
         value="<?php if (isset($_POST['password'])){
         echo htmlspecialchars($_POST['password'],ENT_QUOTES);}?>">
        <?php if( $error['password']==='blank'): ?>
            <p class="error">※パスワードを入力してください</p>
        <?php endif; ?>
        <?php if ($error['password']==='length'):?>
            <p class="error">*パスワードは4文字以上で入力してください</p>
        <?php endif ?>
    </dd>
    <dt>写真など</dt>
    <dd>
        <input type="file" name="image" size="35">
        <?php if ($error['image']==='type'): ?>
        <p class="error">※写真などは「.gif」または「.jpg」の画像を指定してください</p>
        <?php endif; ?>
        <?php if ($isError): ?>
        <p class="error">※恐れ入りますが、画像を改めて指定してください</p>
        <?php endif; ?>
        
    </dd>
    </dl>
    <div><input class="join_check green_btn" type="submit" value="入力内容を確認"></div>
    

</form>

</div>
</main>


</body>
</html>


<!-- 
    エラーが出る件
    初期値はempty($error)が使えなくなってしまうので、削除
        !empty($error) && を追加　→失敗
    上を　isset($error['~~'])　に変更　→成功
    actionも同様にisset条件を追加
    inputのvalueの値の中のPOST（書き直しをしたいときにPOSTで値取得
        (isset($_POST['password']))を追加

    長すぎてしまう。いい方法はある？ empty(error)の使い方を改善したほうがよさそう。

-->

