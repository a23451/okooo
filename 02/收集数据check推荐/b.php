<?php 
      
      set_time_limit(0);
      // 假定数据库用户名：root，密码：123456，数据库：RUNOOB 
      header("content-type:text/html;charset=utf-8");
      // $con=mysqli_connect("localhost","root","root","test");
      
    $con=mysqli_connect("sqld-gz.bcehost.com","39c806f237fc4067be5b2dc1a7d7388e","722ace1fb7a440628cfbd1920664fb93","NTiEppnrPkpytXKaPtDp"); 
      if (mysqli_connect_errno($con)) 
      { 
          echo "连接 MySQL 失败: " . mysqli_connect_error(); 
      } 
      mysqli_query($con,'SET NAMES UTF8');

      for ($k=1; $k < 7; $k++) { 
            $okooo_date = date("Y-m-d",strtotime("-".$k." day"));
            echo $okooo_date."<hr>";
            // echo $okooo_date."<hr>";
                  
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


            // $c = '/hideone"><span>(.*?)<\/span>(.*?)blank">(.*?)<\/a>(.*?)<td>(.*?)<\/td>(.*?)ctrl_time">(.*?)<\/span>(.*?)ctrl_homename">(.*?)<\/a>(.*?)ctrl_homescore">(.*?)<\/b>(.*?)ctrl_awayscore">(.*?)<\/b>(.*?)ctrl_awayname">(.*?)<\/a>(.*?)font_red">(.*?)<\/span>(.*?)<span(.*?)>(.*?)<\/span><span(.*?)>(.*?)<\/span><span(.*?)>(.*?)<\/span>(.*?)ctrl_result(.*?)>(.*?)<\/td>/is';

            $c = '/hideone"><span>(.*?)<\/span>(.*?)blank">(.*?)<\/a>(.*?)<td>(.*?)<\/td>(.*?)ctrl_time">(.*?)<\/span>(.*?)ctrl_homename">(.*?)<\/a>(.*?)ctrl_homescore">(.*?)<\/b>(.*?)ctrl_awayscore">(.*?)<\/b>(.*?)ctrl_awayname">(.*?)<\/a>(.*?)font_red">(.*?)<\/span>(.*?)ctrl_result(.*?)>(.*?)<\/td>/is';
            $a = preg_match_all($c, $back_sendmsg, $b);

            // $fp = @fopen("cbll.html", 'a');
            // fwrite($fp, $back_sendmsg);
            // fclose($fp);

            curl_close($ch); 

            // $i = 0;

            // var_dump($b[1][$i]);//编号
            // var_dump($b[3][$i]);//联赛
            // var_dump($b[5][$i]);//时间
            // var_dump($b[7][$i]);//ctrl_time 是否完场
            // var_dump($b[9][$i]);//ctrl_homename 主队
            // var_dump($b[11][$i]);//ctrl_homescore 主队得分
            // var_dump($b[13][$i]);//ctrl_awayname 客队
            // var_dump($b[15][$i]);//ctrl_awayscore 客队得分
            // var_dump($b[17][$i]);//font_red 半场比分
            // var_dump($b[20][$i]);//ctrl_result 赛果310

            $count = count($b[1]);
            // echo $count;
            for ($i=0; $i < $count ; $i++) { 
                  $sql = 'SELECT count(*) AS count FROM okooo_data WHERE okooo_date="'.$okooo_date.'" AND hideone="'.$b[1][$i].'";';
                  $result = mysqli_query($con,$sql);
                  $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
                  if ( $row_result['count'] ){
                        continue;
                  }
                  if ( $b[7][$i] == "未完" ) {
                        continue;
                  }
                  // echo $i;
                  $sql = 'INSERT INTO okooo_data SET hideone="'.$b[1][$i].'" , liansai="'.$b[3][$i].'" , match_time="'.$b[5][$i].'" , ifover="'.$b[7][$i].'" , homename="'.$b[9][$i].'" , homescore="'.$b[11][$i].'" , awayscore="'.$b[13][$i].'" , awayname="'.$b[15][$i].'" , half="'.$b[17][$i].'" , result="'.$b[20][$i].'" , okooo_date="'.$okooo_date.'" ;';
                  if ( $result = mysqli_query($con,$sql) ){
                        // echo $b[1][$i]."<hr>";
                  }else{
                        echo $sql."<hr>";
                  }
                  // echo $sql."<hr>";
            }

      }

      mysqli_close($con);



            // var_dump($b[1][$i]);//编号
            // var_dump($b[3][$i]);//联赛
            // var_dump($b[5][$i]);//时间
            // var_dump($b[7][$i]);//ctrl_time 是否完场
            // var_dump($b[9][$i]);//ctrl_homename 主队
            // var_dump($b[11][$i]);//ctrl_homescore 主队得分
            // var_dump($b[13][$i]);//ctrl_awayname 客队
            // var_dump($b[15][$i]);//ctrl_awayscore 客队得分
            // var_dump($b[17][$i]);//font_red 半场比分
            // // var_dump($b[20][$i]);// 主队赔率
            // // var_dump($b[22][$i]);// 平局赔率
            // // var_dump($b[24][$i]);// 客队赔率
            // var_dump($b[27][$i]);//ctrl_result 赛果310
            

