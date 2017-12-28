<?php 

    set_time_limit(0);
    $con=mysqli_connect("sqld-gz.bcehost.com","39c806f237fc4067be5b2dc1a7d7388e","722ace1fb7a440628cfbd1920664fb93","NTiEppnrPkpytXKaPtDp"); 
    // $con=mysqli_connect("localhost","root","root","test"); 
    if (mysqli_connect_errno($con)) 
    { 
        echo "连接 MySQL 失败: " . mysqli_connect_error(); 
    } 
    mysqli_query($con,'SET NAMES UTF8');


            header("content-type:text/html;charset=utf-8");


            $k = 0;
        while ( $k<2 ) {
            $okooo_date = date("Y-m-d",strtotime("+".$k." day"));
            $k++;
            echo "<hr>".$okooo_date."<br>";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://www.okooo.com/livecenter/jingcai/?date='.$okooo_date);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);        
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5); 
            curl_setopt($ch, CURLOPT_POST, false); 
            $back_sendmsg = curl_exec($ch);
            $back_sendmsg = iconv("gb2312","utf-8//IGNORE",$back_sendmsg);
            // $back_sendmsg = iconv("utf-8","gb2312//IGNORE",$back_sendmsg);

            // $c = '/hideone"><span>(.*?)<\/span>(.*?)blank">(.*?)<\/a>(.*?)<td>(.*?)<\/td>(.*?)ctrl_time">(.*?)<\/span>(.*?)ctrl_homename">(.*?)<\/a>(.*?)ctrl_homescore">(.*?)<\/b>(.*?)ctrl_awayscore">(.*?)<\/b>(.*?)ctrl_awayname">(.*?)<\/a>(.*?)font_red">(.*?)<\/span>(.*?)ctrl_result(.*?)>(.*?)<\/td>/is';


            $c = '/hideone"><span>(.*?)<\/span>(.*?)_blank">(.*?)<\/a>(.*?)<td>(.*?)<\/td>(.*?)ctrl_time">(.*?)<\/span>(.*?)ctrl_homename">(.*?)<\/a>(.*?)rl_awayname">(.*?)<\/a>(.*?)data-num(.*?)>(.*?)<\/span>(.*?)data-num(.*?)>(.*?)<\/span>(.*?)data-num(.*?)>(.*?)<\/span>/is';

            $a = preg_match_all($c, $back_sendmsg, $b);

            // $fp = @fopen("call.html", 'a');
            // fwrite($fp, $back_sendmsg);
            // fclose($fp);

            curl_close($ch); 

            // // var_dump($b[0][23]);
            // var_dump($b[1][23]);//编号
            // var_dump($b[3][23]);//联赛
            // var_dump($b[5][23]);//时间
            // var_dump($b[7][23]);//ctrl_time 是否完场
            // var_dump($b[9][23]);//ctrl_homename 主队
            // var_dump($b[11][23]);//ctrl_awayname 客队
            // var_dump($b[14][23]);// 胜赔率
            // var_dump($b[17][23]);// 平赔率
            // var_dump($b[20][23]);// 负赔率

            $count = count($b[1]);


            for ($i=0; $i < $count ; $i++) { 
                  $sql = 'SELECT count(*) AS count FROM okooo_data1 WHERE okooo_date="'.$okooo_date.'" AND hideone="'.$b[1][$i].'";';
                  $result = mysqli_query($con,$sql);
                  $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
                  if ( $row_result['count'] ){
                    echo $b[1][$i]."__exist<br>";
                        continue;
                  }
                  // if ( $b[7][$i] == "完" ) {
                  //       continue;
                  // }
                  // echo $i;
                  $sql = 'INSERT INTO okooo_data1 SET okooo_date="'.$okooo_date.'" ,hideone="'.$b[1][$i].'" , liansai="'.$b[3][$i].'" , match_time="'.$b[5][$i].'" , ifover="'.$b[7][$i].'" , homename="'.$b[9][$i].'" , awayname="'.$b[11][$i].'" , home_price="'.$b[14][$i].'" , dogfall_price="'.$b[17][$i].'" , away_price="'.$b[20][$i].'" ;';
                  if ( $result = mysqli_query($con,$sql) ){
                        echo $b[1][$i]."<hr>";
                  }else{
                        echo $b[1][23]."====".$sql."<hr>";
                  }
            }
        }   