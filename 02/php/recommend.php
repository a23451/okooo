<?php 
session_start();
header("content-type:text/html;charset=utf8");
require("ctr.php");

$date = strtotime("-30day");
$date = date("Y-m-d",$date);
$obj = new ShowClass();
$con = $obj::$conn;
$wx_name_list = $obj->manager_recommender_list();

if ( count($wx_name_list) == 0 ) {
    header("location:recommender.php");
    exit("no one you can add from here");
}

echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">';
require 'bootstrap.php';
echo '<title></title>
    <style>
        table{
            margin: 30px auto;
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
        #date{
            width:190px;
        }
        .center{
            text-align:center;
        }
        .center a{
            color:#fff;
        }
    </style>
</head>
<body>';
##nav##
require 'nav.php';
##nav##
if ( isset( $_GET['wx_name'] ) ) {
    $wx_name = $_GET['wx_name'];
}else{
    $wx_name = $wx_name_list[0]['wx_name'];
}
echo '<form method="post" action="index.php" class="form-horizontal">
    <input type="text" name="a" value="insert_recommend_data" style="display:none" readonly="readonly">
    <div class="control-group"><label class="control-label">推荐人</label><div class="controls">
    <select class="input-xlarge" id="" name="wx_name" style="width:200px">';
    foreach ($wx_name_list as $key => $one_wx_name) {
        $selected = $wx_name==$one_wx_name['wx_name']?"selected=selected":"";
        echo '<option value="'.$one_wx_name['wx_name'].'" '.$selected.'>'.$one_wx_name['wx_name'].'</option>';
    }
        // <option value="华山论球">华山论球</option>
        // <option value="一哥解盘">一哥解盘</option>   
    $today = date("Y-m-d");
    echo '</select></div></div>

    <div class="control-group"><label class="control-label">日期</label><div class="controls">
        <input class="input-xlarge" type="date" id="date" name="okooo_date" value="'.$today.'">
    </div></div>

    <div class="control-group"><label class="control-label">场次</label><div class="controls">
        <select class="input-xlarge" id="selecter" name="match_no_before" style="width:200px">
        </select>
    </div></div>

    <div class="control-group"><label class="control-label"></label><div class="controls">
    <select class="input-xlarge" id="" name="match_result_c" style="width:100px">
        <option style="background: skyblue" value="310,3,0">胜</option>
        <option style="background: skyblue" value="310,1,0">平</option>
        <option style="background: skyblue" value="310,0,0">负</option>
        <option style="background: skyblue" value="310,3-1,0">胜平</option>
        <option style="background: skyblue" value="310,1-0,0">平负</option>

        <option style="background: orange" value="+,1,>">+1胜</option>
        <option style="background: orange" value="+,2,>">+2胜</option>
        <option style="background: orange" value="+,3,>">+3胜</option>
        <option style="background: orange" value="+,4,>">+4胜</option>
        <option style="background: orange" value="-,1,>">-1胜</option>
        <option style="background: orange" value="-,2,>">-2胜</option>
        <option style="background: orange" value="-,3,>">-3胜</option>
        <option style="background: orange" value="-,4,>">-4胜</option>

        <option style="background: skyblue" value="+,1,<">+1负</option>
        <option style="background: skyblue" value="+,2,<">+2负</option>
        <option style="background: skyblue" value="+,3,<">+3负</option>
        <option style="background: skyblue" value="+,4,<">+4负</option>
        <option style="background: skyblue" value="-,1,<">-1负</option>
        <option style="background: skyblue" value="-,2,<">-2负</option>
        <option style="background: skyblue" value="-,3,<">-3负</option>
        <option style="background: skyblue" value="-,4,<">-4负</option>

        <option style="background: orange" value="count,0-1,0">进球数0,1</option>
        <option style="background: orange" value="count,1-2,0">进球数1,2</option>
        <option style="background: orange" value="count,2-3,0">进球数2,3</option>
        <option style="background: orange" value="count,3-4,0">进球数3,4</option>
        <option style="background: orange" value="count,4-5,0">进球数4,5</option>
        <option style="background: orange" value="count,5-6,0">进球数5,6</option>
        <option style="background: orange" value="count,5-6,0">进球数6,7</option>
        <option style="background: orange" value="count,0-1-2,0">进球数0,1,2</option>
        <option style="background: orange" value="count,1-2-3,0">进球数1,2,3</option>
        <option style="background: orange" value="count,3-4-5,0">进球数3,4,5</option>
    </select></div></div>

    <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <label class="radio">
                <input type="radio" value="0" checked="checked" name="update">
                插入这一天数据
            </label>
            <label class="radio">
                <input type="radio" value="1" name="update">
                更改这一天数据
            </label>
    </div></div>

    <div class="control-group">
    <label class="control-label"></label>
    <div class="controls">
            <input type="checkbox" name="tinggeng">今天停更
    </div></div>

    <div class="control-group">
    <label class="control-label"></label>
    <div class="controls">
    <button class="btn btn-primary">插入一条</button>
    </div></div>
    
    
</fieldset>
</form><hr>';
echo '<div class="center">
        <div class="control-group"><label class="control-label"></label><div class="controls">
        <button class="btn btn-info"><a href="/okooo/select_update_data/d.php?recommender='.$wx_name.'">更新结果</a></button>
    </div></div></div>
    <form method="get" action="'.$_SERVER["PHP_SELF"].'" class="form-horizontal"><fieldset>
    <div class="control-group"><label class="control-label"></label><div class="controls">
        <button class="btn btn-info">选一下，在下面显示ta的记录</button>
    </div></div>
    <div class="control-group"><label class="control-label"></label><div class="controls">
    <select id="" name="wx_name" style="width:200px" class="input-xlarge">';
foreach ($wx_name_list as $key => $one_wx_name) {
    $selected = $wx_name==$one_wx_name['wx_name']?"selected=selected":"";
    echo '<option value="'.$one_wx_name['wx_name'].'" '.$selected.'>'.$one_wx_name['wx_name'].'</option>';
}
echo '</select></div></div>    
    </fieldset></form>';

$row_result2 = $obj->all_right_count($wx_name);
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

$one_result_list = $obj->one_wx_name_list($wx_name);


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

<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
    var dateObj = document.getElementById('date');
    var selectObj = document.getElementById('selecter');
    dateObj.onchange=function(argument) {
        console.log(dateObj.value)
        selectObj.innerHTML = "";
        $.get("index.php?a=someday_match_data", { date: dateObj.value },
                function(data){
                    var data = JSON.parse(data);
                    for (var i = data.length - 1; i >= 0; i--) {
                        console.log(data[i]['hideone']);
                        var option = document.createElement("option");
                        option.value = data[i]['hideone'];
                        if ( i%2==0 ) { option.style = "background-color:skyblue"; }
                        if ( data[i]['have_score'] == 'false' ) {
                            option.text = data[i]['hideone']+"  "+data[i]['homename']+"vs"+data[i]['awayname']+data[i]['home_price']+"--"+data[i]['dogfall_price']+"--"+data[i]['away_price'];
                        }else{
                            option.text = data[i]['hideone']+"  "+data[i]['homename']+"vs"+data[i]['awayname']+data[i]['homescore']+"--"+data[i]['awayscore'];
                        }
                        selectObj.appendChild(option);
                    }
        });
    }
    document.onload = quest_data();
    function quest_data() {
        console.log(dateObj.value)
        selectObj.innerHTML = "";
        $.get("index.php?a=someday_match_data", { date: dateObj.value },
                function(data){
                    var data = JSON.parse(data);
                    for (var i = data.length - 1; i >= 0; i--) {
                        console.log(data[i]['hideone']);
                        var option = document.createElement("option");
                        option.value = data[i]['hideone'];
                        if ( i%2==0 ) { option.style = "background-color:skyblue"; }
                        if ( data[i]['have_score'] == 'false' ) {
                            option.text = data[i]['hideone']+"  "+data[i]['homename']+"vs"+data[i]['awayname']+data[i]['home_price']+"--"+data[i]['dogfall_price']+"--"+data[i]['away_price'];
                        }else{
                            option.text = data[i]['hideone']+"  "+data[i]['homename']+"vs"+data[i]['awayname']+data[i]['homescore']+"--"+data[i]['awayscore'];
                        }
                        selectObj.appendChild(option);
                    }
        });
    }


</script>
</body>