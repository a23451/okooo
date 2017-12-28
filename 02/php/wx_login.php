<?php 
session_start();
header("content-type:text/html;charset=utf8");

if ( !isset($_GET['openid']) ) {
    exit("20001");
}

###mysql_connect###
$con=mysqli_connect("sqld-gz.bcehost.com","39c806f237fc4067be5b2dc1a7d7388e","722ace1fb7a440628cfbd1920664fb93","NTiEppnrPkpytXKaPtDp"); 
// $con=mysqli_connect("localhost","root","root","test"); 
if (mysqli_connect_errno($con)) 
{ 
  echo "连接 MySQL 失败: " . mysqli_connect_error(); 
  exit;
} 
$result = mysqli_query( $con,"set names utf8;" );
###mysql_connect###

$arr = $_GET;

if( !isset($arr['province']) || !isset($arr['openid']) || !isset($arr['nickname']) || !isset($arr['sex']) || !isset($arr['headimgurl']) ) {
    exit("error:20002");
}


$sql = 'SELECT count(*) as count from manager where wx_openid="'.$arr['openid'].'"';
$result = mysqli_query( $con,$sql );
$row_result = mysqli_fetch_array($result);

if( $row_result['count'] == 0 ){
    $sql = 'INSERT INTO manager set username="'.$arr['nickname'].'",mag_id="'.$arr['openid'].'",password="'.$arr['openid'].'",wx_name="'.$arr['nickname'].'",wx_openid="'.$arr['openid'].'",wx_province="'.$arr['province'].'",wx_headimgurl="'.$arr['headimgurl'].'",create_time=now(),login_times="1",last_login=now();';
}else{
    $sql = 'UPDATE manager set wx_name="'.$arr['nickname'].'",wx_province="'.$arr['province'].'",wx_headimgurl="'.$arr['headimgurl'].'",login_times=login_times+1,last_login=now() WHERE wx_openid="'.$arr['openid'].'"';
}

$result = mysqli_query( $con,$sql );
if( !$result ){
    exit("login fail登录失败");
}

if ( $result ) {
    $sql = 'select have_source,source_tag from manager where wx_openid="'.$arr['openid'].'";';
    $result = mysqli_query($con,$sql);
    $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if ( $row_result['have_source'] =='true' ) {
        $_SESSION['have_source'] =='true';
    }
}

$_SESSION['mag_id'] = $arr['openid'];
$_SESSION['user'] = $arr['nickname'];

if ( $_SESSION['mag_id'] && $_SESSION['user'] ) {
    header("location:/okooo/okooo/showpublic.php");
}

exit;