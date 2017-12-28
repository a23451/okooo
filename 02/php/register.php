
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<title></title>
<?php 
if ( isset($_GET['msg']) ) {
	if ( $_GET['msg']==1 ) {
		echo '<script>alert("用户名已存在")</script>';
	}
	if ( $_GET['msg']==4 ) {
		echo '<script>alert("参数错误")</script>';
	}
	if ( $_GET['msg']==6 ) {
		echo '<script>alert("不能超过10个汉字或30个字母数字")</script>';
	}
}
 ?>
	<style type="text/css">
		*{
			text-align: center;
			font-size: 18px;
			color: #333;
		}
		input{
			border: 1px black solid;
			padding: 15px;
		}
		button{
			padding: 15px;
			background-color: skyblue;
			color:#fff;
			width:300px;
		}
		form{
			margin-top: 180px;
		}
	</style>
</head>
<body>
<form action="index.php?a=register" method="POST">
	<p><input type="text" name="username" placeholder="用户名">	</p>
	<p><input type="password" name="password" placeholder="密码"></p>
	<p><button>注册</button></p>
</form>
</body>
</html>