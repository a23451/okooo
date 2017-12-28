<?php 


if ( isset($_SESSION['have_source']) ) { $nav_width = "16%"; }else{ $nav_width = "20%"; }
echo '<div class="sub_nav" style="width:'.$nav_width.'"><button class=""><a href="showme.php">查看记录</button></div>';
echo '<div class="sub_nav" style="width:'.$nav_width.'"><button class=""><a href="recommender.php">管理推荐人</button></div>';
echo '<div class="sub_nav" style="width:'.$nav_width.'"><button class=""><a href="recommend.php">管理推荐记录</a></button></div>';
if ( isset($_SESSION['have_source']) ) {
	if ( $_SESSION['have_source'] == "true" ) {
		echo '<div class="sub_nav" style="width:'.$nav_width.'"><button class=""><a href="showother.php">查看所有记录</a></button></div>';
	}
}
if ( isset($_SESSION['mag_id']) ) {
	$login_out = "退出:".$_SESSION['user'];
}else{
	$login_out = "登录";
}
echo '<div class="sub_nav" style="width:'.$nav_width.'"><button class=""><a href="showpublic.php">公告推荐</a></button></div>';
echo '<div class="sub_nav" style="width:'.$nav_width.'"><button class=""><a href="index.php?a=logout">'.$login_out.'</a></button></div>';
