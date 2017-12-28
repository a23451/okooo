<?php 

/**
* 
*/
class ShowClass
{
    static $conn;
    function __construct()
    {
        if ( !isset($_SESSION['mag_id']) && $_GET['a'] !='login' && $_GET['is_wx'] !='true' ) {
            header("location:login.php");
        }
        if ( $_SERVER['HTTP_HOST'] =="localhost" ) {
            $con=mysqli_connect("localhost","root","root","test");
        }elseif ( $_SERVER['HTTP_HOST'] =="limingliming.xyz" ) {
            $con=mysqli_connect("sqld-gz.bcehost.com","39c806f237fc4067be5b2dc1a7d7388e","722ace1fb7a440628cfbd1920664fb93","NTiEppnrPkpytXKaPtDp"); 
        }
        // $con=mysqli_connect("localhost","root","root","test");
        // $con=mysqli_connect("sqld-gz.bcehost.com","39c806f237fc4067be5b2dc1a7d7388e","722ace1fb7a440628cfbd1920664fb93","NTiEppnrPkpytXKaPtDp"); 
        if (mysqli_connect_errno($con)) 
        { 
          echo "连接 MySQL 失败: " . mysqli_connect_error(); 
          exit;
        } 
        mysqli_query( $con,"set names utf8;" );
        self::$conn = $con;
    }

    ###wx###
    public function wx_list()
    {
        header("content-type:text/html;charset=utf8");
        $sql = 'select wx_name,recommender_id from recommender where mag_id="46831512482494"';
        $result = mysqli_query(self::$conn,$sql);
        $return_array = array();
        while ( $row_result = mysqli_fetch_array($result,MYSQL_ASSOC) ) {
            $recommender_id = $row_result['recommender_id'];
            $arr = array();
            // $arr['recommender_id'] = $row_result['recommender_id'];
            $arr['wx_name'] = $row_result['wx_name'];

            #总推荐数，推中数，推中率
            $sql2 = 'select count(match_no_before) as count,sum(recommend_result) as sum from recommend_data left join okooo_data on recommend_data.okooo_date=okooo_data.okooo_date and recommend_data.match_no_before=okooo_data.hideone WHERE recommender_id="'.$recommender_id.'" and recommend_data.okooo_date>"'.$date.'" and match_no_before!="" and recommend_result!="a" ;';
            $result2 = mysqli_query(self::$conn,$sql2);
            $row_result2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
            $arr['count'] = $row_result2['count'];
            $arr['sum'] = $row_result2['sum'];
            $right_percent = number_format( $row_result2["sum"] / $row_result2["count"] , 4 ) * 100;
            $arr['right_percent'] = $right_percent;
            // $sql = 'select * from recommend_data where recommender_id="'.$recommender_id.'"';
            #近30天推荐数据
            $days = 30;
            $date = strtotime("-".$days."day");
            $date = date("Y-m-d",$date);
            $sql = 'select recommend_data.okooo_date,match_no_before,match_name_okooo,homename,awayname,homescore,awayscore,match_result_c,recommend_result,comment from recommend_data left join okooo_data on recommend_data.okooo_date=okooo_data.okooo_date and recommend_data.match_no_before=okooo_data.hideone WHERE recommender_id="'.$recommender_id.'" and recommend_data.okooo_date>"'.$date.'" order by recommend_data.okooo_date desc;';
            $result_2 = mysqli_query(self::$conn,$sql);
            $arr_2 = array();
            while ( $row_result_2 = mysqli_fetch_array($result_2,MYSQL_ASSOC) ) {
                    $rec = explode(',', $row_result_2['match_result_c']);
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
                $row_result_2['rec_str'] = $rec_str;

                if ( $row_result_2['recommend_result'] == 1 ) {
                    $row_result_2['color'] = 'red';
                }elseif( $row_result_2['recommend_result'] == 'a' ){
                    $row_result_2['color'] = 'skyblue';
                    $row_result_2['recommend_result'] = '无';
                }else{
                    $row_result_2['color'] = '#ccc';
                }

                $arr_2[] = $row_result_2;
            }
            $arr['data'] = $arr_2;
            $return_array[] = $arr;

        }
        echo json_encode($return_array);

    }
    ###wx###
    
    public function wx_name_list()
    {
        header("content-type:text/html;charset=utf8");
        $sql = "select wx_name from recommend_data group by wx_name";
        $result = mysqli_query(self::$conn,$sql);
        $arr_wx_name = null;
        while ( $row_result = mysqli_fetch_array($result) ) {
            $arr_wx_name[] = $row_result['wx_name'];
        }
        return $arr_wx_name;

    }

    #一位推荐人的近30天总推荐数与推中数；
    public function all_right_count($wx_name,$username=false)
    {
        if ( isset($_POST['limit']) ) {
            if ( $_POST['limit'] == "all" ) {
                $days = 1000;
            }else{
                $days = intval($_POST['limit']);
            }
        }else{
            $days = 30;
        }
        if ( $username  ) {
             $sql = 'select mag_id from manager where username="'.$username.'";';
            $result = mysqli_query(self::$conn,$sql);
            $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if ( $row_result ) {
                $mag_id = $row_result['mag_id'];
            }
        }else{
            $mag_id = $_SESSION['mag_id'];
        }
        $recommender_id = $mag_id.'_'.$wx_name;
        $date = strtotime("-".$days."day");
        $date = date("Y-m-d",$date);
        $sql2 = 'select count(match_no_before) as count,sum(recommend_result) as sum from recommend_data left join okooo_data on recommend_data.okooo_date=okooo_data.okooo_date and recommend_data.match_no_before=okooo_data.hideone WHERE recommender_id="'.$recommender_id.'" and recommend_data.okooo_date>"'.$date.'" and match_no_before!="" and recommend_result!="a" ;';
        $result2 = mysqli_query(self::$conn,$sql2);
        $row_result2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
        return $row_result2;

    }


    #
    public function one_wx_name_list($wx_name,$username=false)
    {
        if ( isset($_POST['limit']) ) {
            if ( $_POST['limit'] == "all" ) {
                $days = 1000;
            }else{
                $days = intval($_POST['limit']);
            }
        }else{
            $days = 30;
        }
        if ( $username  ) {
             $sql = 'select mag_id from manager where username="'.$username.'";';
            $result = mysqli_query(self::$conn,$sql);
            $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if ( $row_result ) {
                $mag_id = $row_result['mag_id'];
            }
        }else{
            $mag_id = $_SESSION['mag_id'];
        }
        $recommender_id = $mag_id.'_'.$wx_name;
        $date = strtotime("-".$days."day");
        $date = date("Y-m-d",$date);
        $sql = 'select recommend_data.okooo_date,match_no_before,match_name_okooo,homename,awayname,homescore,awayscore,match_result_c,recommend_result,comment from recommend_data left join okooo_data on recommend_data.okooo_date=okooo_data.okooo_date and recommend_data.match_no_before=okooo_data.hideone WHERE recommender_id="'.$recommender_id.'" and recommend_data.okooo_date>"'.$date.'" order by recommend_data.okooo_date desc;';
        $result = mysqli_query(self::$conn,$sql);
        $arr = array();
        while ( $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC) ) {
            $arr[] = $row_result;
        }
        return $arr;
    }


    #
    function manager_recommender_list( $username=false )
    {
        header("content-type:text/html;charset=utf8");

        if ( isset($_SESSION['mag_id']) ) {
            $mag_id = $_SESSION['mag_id'];
        }
        if ( $username  ) {
             $sql = 'select mag_id from manager where username="'.$username.'";';
            $result = mysqli_query(self::$conn,$sql);
            $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if ( $row_result ) {
                $mag_id = $row_result['mag_id'];
            }else{

            }
        }
        if( !isset($mag_id) ){
            header("location:login.php?100013");
            exit("error:10002");
        }
        $sql = 'select wx_name from recommender where mag_id="'.$mag_id.'" order by create_time;';
        $result = mysqli_query(self::$conn,$sql);
        if ( $result->num_rows==0 ) {
            $arr = array();
            return $arr;
        }
        $arr = array();
        while ( $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC) ) {
            $arr[] = $row_result;
        }
        return $arr;
    }


    function public_recommend_user()
    {
        $sql = 'select belong_mag_id,public_recommend from source left join manager on source.source_tag=manager.source_tag where manager.mag_id="'.$_SESSION['mag_id'].'";';
        $result = mysqli_query(self::$conn,$sql);
        if ( $result->num_rows==0 ) {
            $_SESSION['public_recommend'] = 'liming';
            return;
        }
        $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if ( $row_result['public_recommend']==' ' || $row_result['public_recommend']=='' ) {
            $_SESSION['public_recommend'] = 'liming';
        }else{
            $sql = 'select username from manager where mag_id="'.$row_result['belong_mag_id'].'";';
            $result = mysqli_query(self::$conn,$sql);
            $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $_SESSION['public_recommend'] = $row_result['username'];
        }
    }


    function add_recommender()
    {
        header("content-type:text/html;charset=utf8");
        if ( isset($_POST['name']) ) {
            $recommender_name = $_POST['name'];
        }else{
            header("location:".$_SERVER['HTTP_REFERER'])."?msg=10007";
            exit;
        }
        if( strlen( $recommender_name )>30 || strlen( $recommender_name )==0 ){
            header("location:".$_SERVER['HTTP_REFERER']."?msg=10008");
            exit;
        }
        $mag_id = $_SESSION['mag_id'];

        $sql = 'select count(*) as count from recommender where mag_id="'.$mag_id.'" ;';
        $result = mysqli_query(self::$conn,$sql);
        $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if ( $row_result['count'] > 19 ) {
            header("location:".$_SERVER['HTTP_REFERER']."?msg=aleady10");
            exit;
        }

        $recommender_id = $mag_id.'_'.$recommender_name;
        $sql = 'select count(*) as count from recommender where recommender_id="'.$recommender_id.'" ;';
        $result = mysqli_query(self::$conn,$sql);
        $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if ( $row_result['count'] > 0 ) {
            header("location:".$_SERVER['HTTP_REFERER']."?msg=name_exist");
            exit;
        }else{
            $sql = 'INSERT INTO recommender SET recommender_id="'.$recommender_id.'",wx_name="'.$recommender_name.'",mag_id="'.$mag_id.'",create_time=now(),update_time=now() ;';
            $result = mysqli_query(self::$conn,$sql);
            if ( $result ) {
                header("location:".$_SERVER['HTTP_REFERER']."?msg=add_ok");
            }else{
                header("location:".$_SERVER['HTTP_REFERER']."?msg=add_fail");
            }
        }
    }


    function edit_recommender()
    {
        header("content-type:text/html;charset=utf8");
        if ( !isset($_POST) ) {
            header("location:".$_SERVER['HTTP_REFERER']."?msg=10009");
            exit;
        }
        $mag_id = $_SESSION['mag_id'];
        foreach ($_POST as $key => $value) {
            $recommender_id = $mag_id.'_'.$key;
            $sql = 'select count(*) as count,wx_name from recommender where recommender_id="'.$recommender_id.'" ;';
            $result = mysqli_query(self::$conn,$sql);
            $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if ( $row_result['count'] > 0 ) {
                if ( $row_result['wx_name'] == $value ) {
                    continue;
                }
                $new_recommender_id = $mag_id.'_'.$value;
                #更新recommender
                $sql = 'UPDATE recommender SET wx_name="'.$value.'",recommender_id="'.$new_recommender_id.'",update_time=now() WHERE recommender_id="'.$recommender_id.'" ;';
                $result = mysqli_query(self::$conn,$sql);
                #更新recommend_data
                $sql = 'select count(*) as count recommend_data where recommender_id="'.$recommender_id.'" ;';
                $result = mysqli_query(self::$conn,$sql);
                $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
                if ( $row_result['count'] > 0 ) {
                    $sql = 'UPDATE recommend_data SET wx_name="'.$value.'",recommender_id="'.$new_recommender_id.'",update_time=now() WHERE recommender_id="'.$recommender_id.'" ;';
                    $result = mysqli_query(self::$conn,$sql);
                    if ( !$result ) {
                        exit("error:20002");
                    }
                }
                echo $sql;
            }
        }
        header("location:".$_SERVER['HTTP_REFERER']);
        var_dump($_POST);
    }


    function delete_recommender()
    {
        header("content-type:text/html;charset=utf8");
        if ( isset($_GET['name']) ) {
            $wx_name = $_GET['name'];
        }else{
            header("location:".$_SERVER['HTTP_REFERER']);
            exit;
        }
        $mag_id = $_SESSION['mag_id'];
        $recommender_id = $mag_id.'_'.$wx_name;
        $sql = 'select count(*) as count from recommender where recommender_id="'.$recommender_id.'" ;';
        $result = mysqli_query(self::$conn,$sql);
        if ( !$result ) {
            header("location:".$_SERVER['HTTP_REFERER']."?error=delete_fail_01");
            exit;
        }else{
            $mag_id = "old_".$mag_id;
            $sql = 'UPDATE recommender SET mag_id="'.$mag_id.'" where recommender_id="'.$recommender_id.'" ;';
            $result = mysqli_query(self::$conn,$sql);
            if ( $result ) {
                header("location:".$_SERVER['HTTP_REFERER']);
            }else{
                header("location:".$_SERVER['HTTP_REFERER']."?msg=delete_fail_02");
            }
        }
    }


    function someday_match_data()
    {
        $okooo_date = $_GET['date'];

        $yestoday = date("Y-m-d",strtotime("+1 day"));
        $tomorrow = date("Y-m-d",strtotime("-1 day"));
        if ( $okooo_date == date("Y-m-d") || $okooo_date == $tomorrow  || $okooo_date == $yestoday ) {
            $database_name = 'okooo_data1';
            $have_score = 'false';
            $sql = 'SELECT okooo_date,hideone,homename,awayname,home_price,away_price,dogfall_price FROM '.$database_name.' WHERE okooo_date ="'.$okooo_date.'" order by hideone desc;';
        }else{
            $database_name = 'okooo_data';
            $have_score = 'true';
            $sql = 'SELECT okooo_date,hideone,homename,awayname,homescore,awayscore FROM '.$database_name.' WHERE okooo_date ="'.$okooo_date.'" order by hideone desc;';
        }
     
        $result = mysqli_query(self::$conn,$sql);
        if (    $result->num_rows == 0 ) {
            echo "0 no data";
            exit;
        }else{
            
        }

        $arr = array();
        while ( $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC) ) {
            $row_result['have_score'] = $have_score;
            $arr[] = $row_result;
        }
        echo json_encode( $arr );
        exit; 
    }


    function insert_recommend_data()
    {
        $fp = fopen('./log/get_post.log', 'a+');
        fwrite($fp, date("Y-m-d H:i:s").json_encode($_POST)."\n");

        if ( $_SERVER['HTTP_HOST'] == "localhost" ) {
            $arr = $_POST;
            $arr['mag_id'] = '46831512482494';
            $url = 'https://limingliming.xyz/okooo/okooo/index.php?a=insert_recommend_data';
            $this->http_post($url,$arr);
        }
        var_dump($_POST);
        if ( isset($_POST['wx_name']) ) {
            $wx_name = $_POST['wx_name'];
        }else{
            exit( "no wx_name" );
        }
        if ( isset($_POST['okooo_date']) ) {
            $okooo_date = $_POST['okooo_date'];
        }else{
            exit( "no okooo_date" );
        }
        if ( isset($_POST['tinggeng']) ) {
            // tinggeng( $con,$wx_name,$okooo_date );
            // exit;
        }else{
            if ( isset($_POST['match_no_before']) ) {
                $match_no_before = $_POST['match_no_before'];
            }else{
                echo "<script>alert('没选场次');</script>";
                header("location:".$_SERVER['HTTP_REFERER']);
                exit;
                exit( "no match_no_before" );
            }

            if ( isset($_POST['match_name_okooo']) ) {
                $match_name_okooo = 'match_name_okooo="'.$_POST['match_name_okooo'].'",';
            }else{
                $match_name_okooo = "";
            }

            if ( isset($_POST['match_result_c']) ) {
                $match_result_c = $_POST['match_result_c'];
            }else{
                exit( "no match_result_c" );
            }
        }
        if ( isset($_SESSION['mag_id']) ) {
            $mag_id = $_SESSION['mag_id'];
        }elseif( isset($_POST['mag_id']) ){
            $mag_id = $_POST['mag_id'];
        }
        $recommender_id = $mag_id.'_'.$wx_name;
        $sql = 'select count(*) as count from recommend_data where recommender_id="'.$recommender_id.'" and okooo_date="'.$okooo_date.'" ;';
        $result = mysqli_query(self::$conn,$sql);
            fwrite($fp,$sql."\n");
        $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $c = '/(.*?)\?/i';
        $a = preg_match_all($c, $_SERVER['HTTP_REFERER'], $b);
        if ( $a ) {
            $c = '/(.*?)\?(.*?)&/i';
            $a = preg_match_all($c, $_SERVER['HTTP_REFERER'], $b);
            if ( $a ) {
                $url_referer = $b[0][0];
            }else{
                $url_referer = $_SERVER['HTTP_REFERER']."&";
            }
        }else{
            $url_referer = $b[1][0]."?";
        }

        if ( $row_result['count'] == 0 ) {
            if ( isset($_POST['tinggeng']) ) {
                $sql = 'INSERT INTO recommend_data set recommend_result="a",checked="1",match_no_before="", wx_name="'.$wx_name.'",okooo_date="'.$okooo_date.'",comment="停更",recommender_id="'.$recommender_id.'",create_time=now(); ';
            }else{
                $sql = 'INSERT INTO recommend_data set recommend_result="a",checked="0",'.$match_name_okooo.' wx_name="'.$wx_name.'",recommender_id="'.$recommender_id.'",okooo_date="'.$okooo_date.'",match_no_before="'.$match_no_before.'",match_result_c="'.$match_result_c.'",create_time=now(); ';
            }
            fwrite($fp,$sql."\n");
            $result = mysqli_query(self::$conn,$sql);
            if ( !$result ) {
                header("location:".$url_referer."msg=insert_fail");
                exit;
            }else{
                header("location:".$url_referer."msg=insert_ok");
                exit;
            }
        }elseif ( isset($_POST['update']) ) {
            if ( $_POST['update'] == 0 ) {
                header("location:".$url_referer."msg=".$wx_name.'_'.$okooo_date."_exist");
                exit;
            }
            if ( isset($_POST['tinggeng']) ) {
                $sql = 'UPDATE recommend_data set recommend_result="a",checked="1",match_no_before="",comment="停更",update_time=now() WHERE (recommender_id="'.$recommender_id.'" and okooo_date="'.$okooo_date.'"); ';
            }else{
                $sql = 'UPDATE recommend_data set recommend_result="a",checked="0",'.$match_name_okooo.'comment="", match_no_before="'.$match_no_before.'",match_result_c="'.$match_result_c.'",update_time=now() WHERE recommender_id="'.$recommender_id.'" AND okooo_date="'.$okooo_date.'"; ';
            }
            fwrite($fp,$sql."\n");
            $result = mysqli_query(self::$conn,$sql);
            if ( !$result ) {
                header("location:".$url_referer."msg=insert_fail");
                exit;
            }else{
                header("location:".$url_referer."msg=insert_ok");
                exit;
            }
            
        }else{
            header("location:".$url_referer."msg=".$wx_name.'_'.$okooo_date."_exist");
            exit;
        }
        fclose($fp);
        
    }


    function source_manager_list()
    {
        $mag_id = $_SESSION['mag_id'];
        $sql = 'select source_tag from source where belong_mag_id="'.$mag_id.'";';
        $result = mysqli_query(self::$conn,$sql);
        $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $source_tag = $row_result['source_tag'];

        $sql = 'select username from manager where source_tag="'.$source_tag.'";';
        $result = mysqli_query(self::$conn,$sql);
        $arr = array();
        while ( $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC) ) {
            $arr[] = $row_result;
        }
        return $arr;
    }


    function register()
    {
        if ( isset($_POST['username']) && isset($_POST['username']) ) {
            $username = $_POST['username'];
            $password = $_POST['password'];
        }else{
            header("location:".$_SERVER['HTTP_REFERER']."?msg=5");
            exit;
        }
        if( strlen( $username )>30 || strlen( $password )>30 || strlen( $username )==0 || strlen( $password )==0 ){
            header("location:".$_SERVER['HTTP_REFERER']."?msg=6");
            exit;
        }
        $sql = 'select count(*) as count from manager where username="'.$username.'" ;';
        $result = mysqli_query(self::$conn,$sql);
        $arr = array();
        $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if ( $row_result['count'] > 0 ) {
            header("location:register.php?msg=1");
            exit;
        }else{
            do{
                $mag_id = rand(1000,9999).time();
                $sql = 'select count(*) as count from manager where mag_id="'.$mag_id.'";';
                $result = mysqli_query(self::$conn,$sql);
                $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
            }while ( $row_result['count'] >0 );
            $source_tag = '';
            if ( isset($_GET['source']) ) {
                $source = $_GET['source'];
                $sql = 'select source_tag from source;';
                $result = mysqli_query(self::$conn,$sql);
                $arr = array();
                while ($row_result = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $arr[] = $row_result['source_tag'];
                }
                if ( in_array($source,$arr) ) {
                    $source_tag = ',source_tag="'.$source.'"';
                }else{
                    header("location:".$_SERVER['HTTP_REFERER']."?msg=4");
                    exit;
                }
            }
            $sql = 'INSERT INTO manager SET username="'.$username.'",password="'.$password.'",mag_id="'.$mag_id.'",create_time=now(),update_time=""'.$source_tag.';';
            $result = mysqli_query(self::$conn,$sql);
            if ( $result ) {
                header("location:login.php?msg=2");
            }
        }

    }


    function login()
    {
        if ( isset($_POST['username']) && isset($_POST['username']) ) {
            $username = $_POST['username'];
            $password = $_POST['password'];
        }else{
            header("location:".$_SERVER['HTTP_REFERER']."?msg=5");
            exit;
        }
        if( strlen( $username )>30 || strlen( $password )>30 || strlen( $username )==0 || strlen( $password )==0 ){
            header("location:".$_SERVER['HTTP_REFERER']."?msg=6");
            exit;
        }
        $sql = 'select count(*) as count from manager where username="'.$username.'" and password="'.$password.'" ;';
        $result = mysqli_query(self::$conn,$sql);
        $fp = fopen('./a.txt', 'a+');
        fwrite($fp, date("Y-m-d H:i:s")." ".$sql." ==== \n");
        fclose($fp);
        $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if ( $row_result['count'] > 0 ) {
            $sql = 'select mag_id,have_source,source_tag from manager where username="'.$username.'" and password="'.$password.'" ;';
            $result = mysqli_query(self::$conn,$sql);
            $row_result = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $mag_id = $row_result['mag_id'];
            $_SESSION['mag_id'] = '';
            $_SESSION['have_source'] = '';
            $_SESSION['mag_id'] = $mag_id;
            $_SESSION['user'] = $username;
            if ( $row_result['have_source'] == "true" ) {
                $_SESSION['have_source'] = "true";
            }

            $sql = 'update manager set login_times=login_times+1,last_login=now() where username="'.$username.'" ;';
            $result = mysqli_query(self::$conn,$sql);

            header("location:showme.php");
            exit;
        }else{
            header("location:".$_SERVER['HTTP_REFERER']."?msg=3");
            exit;
        }
    }


    function logout()
    {
        session_destroy();
        header("location:login.php?msg=logout_ok");
        exit;
    }


    function http_post($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $head = curl_exec($ch); 
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);
        $fp = fopen('./log/http_post.log', 'a+');
        fwrite($fp, date("Y-m-d H:i:s").$httpCode."\n");
        fclose($fp);

    }
}
// $obj = new ShowClass();
// $obj->one_wx_name_list("一哥解盘");
// $obj->test();
