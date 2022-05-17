<?php
session_start();
require('dbconnect.php');
if (empty($_REQUEST['id'])) {
	header('Location: index.php'); exit();
}
// 投稿を取得する
$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p 
 WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST['id']));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" type="text/css" href="style.css">
    <!--zoom.css-->
    <link rel="stylesheet" type="text/css" href="../zoom.css">

</head>

<body>
			<p><a class="menu" href="index.php">一覧にもどる</a></p>
			<?php if ($post = $posts->fetch()):?>
			 <div class="msg msg_box">
			 	<p class="name"><?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?></p>
				<span id="expantion">
				<img class="icon" src="member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES); ?>" 
				 width="80" height="80" alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>" data-action="zoom"/>
				</span>
				<p class="comment"><?php echo htmlspecialchars($post['message'], ENT_QUOTES);?></p>
				<p class="day"><?php echo htmlspecialchars($post['created'], ENT_QUOTES); ?></p>
			 </div>
			<?php else: ?>
			 <p>その投稿は削除されたか、URLが間違えています</p>
			<?php endif; ?>

			
<!--zoom.js-->
<script src="../../../jquery-3.6.0.min.js"></script>
<script src="../zoom.js" type="text/javascript"></script>
<!--expantion-->
<script type="text/javascript" src="./functions.js"></script>
</body>
</html>
