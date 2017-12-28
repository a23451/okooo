<?php 
session_start();

if ( isset($_POST['a']) ) {
	$a = $_POST['a'];
}elseif( isset($_GET['a']) ){
	$a = $_GET['a'];
}else{
	header("location:".$_SERVER['HTTP_REFERER']);
	exit("error:10001.1");
}
if ( isset($_POST) ) {
    $fp = fopen('./log/log.log', 'a+');
    fwrite($fp, date("Y-m-d H:i:s")."get___".json_encode($_GET)."\n");
    fwrite($fp, "post___". json_encode($_POST)."\n");
    fclose($fp);
}


header("content-type:text/html;charset=utf8");
require './ctr.php';
$obj = new ShowClass();
if ( in_array($a, array('register','login','logout','add_recommender','edit_recommender','delete_recommender','insert_recommend_data','someday_match_data','insert_recommend_data')) ) {
}else{
	exit("error:10001");
}
$obj->$a();

exit;