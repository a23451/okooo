<?php 
session_start();
#循环没有结果的推荐recommend_data.recommend_result != ' '，查询okooo_data判断推荐是否正确，更新recommend_data;


      header("content-type:text/html;charset=utf-8");
      // $con=mysqli_connect("localhost","root","root","test"); 

    $con=mysqli_connect("sqld-gz.bcehost.com","39c806f237fc4067be5b2dc1a7d7388e","722ace1fb7a440628cfbd1920664fb93","NTiEppnrPkpytXKaPtDp"); 
      if (mysqli_connect_errno($con)) 
      { 
          echo "连接 MySQL 失败: " . mysqli_connect_error(); 
      } 
      mysqli_query($con,'SET NAMES UTF8');

      if ( isset($_GET['recommender']) ) {
          $recommender = $_SESSION['mag_id']."_".$_GET['recommender'];
          $recommender = ' AND recommender_id="'.$recommender.'"';
      }elseif ( isset($_GET['isme']) ) {
          
      }else{
            exit("hello");
      }
      

    $sql = 'SELECT wx_name,match_no_before,okooo_date,match_result_c,recommend_result FROM recommend_data WHERE checked="0"'.$recommender.';';
    // $sql = 'SELECT * FROM recommend_data;';
    // $result = mysqli_query($con,$sql);
    // $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
    // foreach ($row_result as $key => $value) {
    //     echo $key."=>".$value."<hr>";
    // }
    // exit;    

    $result = mysqli_query($con,$sql);
    if ( !$result ) {
        $fp = fopen('./sql.log', 'a+');
        fwrite($fp, date("Y-m-d H:i:s")."  false:  ".$sql);
        fwrite($fp, "\n");
    }   
    $k = 1;
    $i = 0;
    while($row_result = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        echo "k:".$k++."  ".$row_result['wx_name'].$row_result['okooo_date']."___";
        $wx_name = $row_result['wx_name'];
        $match_no_before = $row_result['match_no_before'];
        $okooo_date = $row_result['okooo_date'];
        $sql = 'SELECT * FROM okooo_data WHERE okooo_date="'.$row_result['okooo_date'].'" AND hideone="'.$row_result['match_no_before'].'"';
        $okooo_result = mysqli_query($con,$sql);
        if ( !$okooo_result ) {
            $fp = fopen('./sql.log', 'a+');
            fwrite($fp, date("Y-m-d H:i:s")."  false:  ".$sql);
            fwrite($fp, "\n");
        }           
        $okooo_data = mysqli_fetch_array($okooo_result,MYSQLI_ASSOC);
        if ( $okooo_data==null ) {
            echo "no_okoooData:"."__".$row_result['match_no_before']."\n";
            // var_dump($okooo_data);
        }else{
            if ( $okooo_data['ifover']=='完' || '点球完' ) {
                #推荐结果记录方式
                $metch = explode(',',$row_result['match_result_c'] );
                #判断是否推荐正确
                if ( $metch[0] == "+" && $metch[2] == ">"  ) {
                    if ( $okooo_data['homescore'] + $metch[1] > $okooo_data['awayscore'] ) {
                        $recommend_result = '1';
                    }else{
                        $recommend_result = '0';
                    }
                }
                elseif ( $metch[0] == "+" && $metch[2] == "<"  ) {
                    if ( $okooo_data['homescore'] + $metch[1] < $okooo_data['awayscore'] ) {
                        $recommend_result = '1';
                    }else{
                        $recommend_result = '0';
                    }
                }
                elseif ( $metch[0] == "-" && $metch[2] == ">"  ) {
                    if ( $okooo_data['homescore'] - $metch[1] > $okooo_data['awayscore'] ) {
                        $recommend_result = '1';
                    }else{
                        $recommend_result = '0';
                    }
                }
                elseif ( $metch[0] == "-" && $metch[2] == "<"  ) {

                    if ( $okooo_data['homescore'] - $metch[1] < $okooo_data['awayscore'] ) {
                        $recommend_result = '1';
                    }else{
                        $recommend_result = '0';
                    }
                }
                elseif ( $metch[0] == "310" ) {
                    $rec_result = explode( '-' , $metch[1] );
                    if ( in_array($okooo_data['result'], $rec_result) ) {
                        $recommend_result = '1';
                    }else{
                        $recommend_result = '0';
                    }
                }
                elseif ( $metch[0] == "count" ) {
                    $rec_result = explode( '-' , $metch[1] );
                    $score_count = $okooo_data['homescore'] + $okooo_data['awayscore'];
                    if ( in_array( $score_count, $rec_result ) ) {
                        $recommend_result = '1';
                    }else{
                        $recommend_result = '0';
                    }
                }else{
                    $recommend_result = '没有就匹配到是否正确';
                }

                $sql = 'UPDATE recommend_data SET checked="1", match_name_okooo="'.$okooo_data['homename'].'vs'.$okooo_data['awayname'].'",home_score_okooo="'.$okooo_data['homescore'].'",away_score_okooo="'.$okooo_data['awayscore'].'",match_result_okooo="'.$okooo_data['result'].'",recommend_result="'.$recommend_result.'" WHERE (wx_name="'.$wx_name.'" AND match_no_before="'.$match_no_before.'" AND okooo_date="'.$okooo_date.'") ; ';
                $update = mysqli_query($con,$sql);
                if ( !$update ) {
                    $fp = fopen('./d_php_sql.log', 'a+');
                    fwrite($fp, date("Y-m-d H:i:s")."  false:  ".$sql);
                    fwrite($fp, "\n");
                    fclose($fp);
                }
                    $fp = fopen('./d_php_sql.log', 'a+');
                    fwrite($fp, date("Y-m-d H:i:s")."  false:  ".$sql);
                    fwrite($fp, "\n");
                    fclose($fp);
            }
        }
        $i++;
        echo "i:".$i."<br>";
    }

    mysqli_close($con);
    header("location:".$_SERVER['HTTP_REFERER']);

      
