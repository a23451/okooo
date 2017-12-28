<?php 
session_start();
header("content-type:text/html;charset=utf8");
require("ctr.php");




$date = strtotime("-30day");
$date = date("Y-m-d",$date);
$obj = new ShowClass();
$con = $obj::$conn;

$obj->public_recommend_user();
$public_recommend_user = $_SESSION['public_recommend'];
$wx_name_list = $obj->manager_recommender_list( $public_recommend_user );

if ( count($wx_name_list) == 0 ) {
	// header("location:recommender.php");
	echo $public_recommend_user;
	exit("咋 没有 呢");
	exit("no one list");
}

echo '<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">';
require 'bootstrap.php';
echo '<title></title>
	<style>
	    table{
	        margin: 30px;
	        border-collapse:collapse;
	        word-break:break-all;
	        font-size:8px;
	    }
	    table, td, th{
	        border:1px solid black;
	        text-align:center;
	    }
	    td{
	        padding: 10px;
	    }
	    .sub_nav{
	    	background : skyblue;
	    	display: inline-block;
	    }
	    .sub_nav button{
	    	width: 100%;
	    	height: 35px;
	    }
	    .sub_nav a{
	    	text-decoration: none; 
	    }
	    form button,select{
	    	margin:5px;
	    	padding:5px;
	    }
	</style>
</head>
<body>';
##mav##
require "nav.php";
echo $public_recommend_user;
##mav##
if ( isset( $_POST['wx_name'] ) ) {
	$wx_name = $_POST['wx_name'];
}else{
	$wx_name = $wx_name_list[0]['wx_name'];
}
echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'">
    <select id="" name="wx_name" style="width:200px">';
foreach ($wx_name_list as $key => $one_wx_name) {
	$selected = $one_wx_name['wx_name']==$wx_name?"selected=selected":"";
    echo '<option value="'.$one_wx_name['wx_name'].'" '.$selected.'>'.$one_wx_name['wx_name'].'</option>';
}
echo '</select><button>change</button></form>';
if ( isset($_POST['limit']) ) {
	$limit = $_POST['limit'];
}else{
	$limit = 30;
}
echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'">
	<input name="wx_name" value="'.$wx_name.'" readonly="readonly" style="display:none">
    <select id="" name="limit" style="width:200px">
    <option value="30">最近30天</option>
    <option value="60">最近60天</option>
    <option value="100">最近100天</option>
    <option value="all">全部</option>
    </select>
    <button>change</button></from>';

$row_result2 = $obj->all_right_count($wx_name,$public_recommend_user);
if ( $row_result2["count"] ==0 ) {
	$right_percent = 0;
}else{
	$right_percent = number_format( $row_result2["sum"] / $row_result2["count"] , 4 ) * 100;
}

echo '<table ><tr>
		<td style="background:skyblue">'.$wx_name.'</td>
		<td>共推'.$row_result2["count"].'</td>
		<td></td>
		<td></td>
		<td>命中'.$right_percent .'%</td>
		<td>中'.$row_result2["sum"].'</td>
	</tr><tr>
		<td>日期</td>
		<td>场次</td>
		<td>比赛</td>
		<td>赛果</td>
		<td>推荐</td>
		<td>结果</td>
	</tr>';

$one_result_list = $obj->one_wx_name_list($wx_name,$public_recommend_user);


foreach ( $one_result_list as $row_result ) {
	$rec = explode(',', $row_result['match_result_c']);
	$rec_str = '';
	if ( $rec[0]== "+" || $rec[0]== "-" ) {
		if ( $rec[2] == ">" ) {
			$rec_str1 = "胜";
		}else{
			$rec_str1 = "负";
		}
		$rec_str = $rec[0].$rec[1].$rec_str1; 
	}
	if ( $rec[0]=="count" ) {
		$rec_str = "进球数".$rec[1];
	}
	if ( $rec[0]=="310" ) {
		if ( $rec[1] == "3" ) {
			$rec_str = "胜";
		}
		if ( $rec[1] == "1" ) {
			$rec_str = "平";
		}
		if ( $rec[1] == "0" ) {
			$rec_str = "负";
		}
		if ( $rec[1] == "3-1" ) {
			$rec_str = "胜平";
		}
		if ( $rec[1] == "1-0" ) {
			$rec_str = "平负";
		}
	}
	if ( $row_result['recommend_result'] == 1 ) {
		$color = 'red';
	}elseif( $row_result['recommend_result'] == 'a' ){
		$color = 'skyblue';
		$row_result['recommend_result'] = '无';
	}else{
		$color = '#ccc';
	}
	echo "<tr>
		<td>{$row_result['okooo_date']}</td>
		<td>{$row_result['match_no_before']}</td>
		<td>{$row_result['homename']}vs{$row_result['awayname']}</td>
		<td>{$row_result['homescore']}-{$row_result['awayscore']}</td>
		<td>{$rec_str}</td>
		<td style='background:".$color."'>{$row_result['recommend_result']}</td>
	</tr>";
}

?>