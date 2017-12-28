<?php 
session_start();
require 'ctr.php';
$obj = new ShowClass();
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
<?php 
require 'bootstrap.php';
 ?>
	<title></title>
	<style type="text/css">
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
	    *{
	    	text-align: center;
	    }
	    input{
	    	margin: 5px;
	    	border: 1px black solid;
	    }
	    form{
	    	font-size: 16px;
	    }
	    input,button{
	    	padding: 5px;
	    }
	    table{
	    	font-size: 8px;
	    }
	</style>
</head>
<body>
<?php 
require 'nav.php';
?>

<form action="index.php?a=add_recommender" method="POST">
	<input type="text" name="name" placeholder="新增的名称">
	<button>增加这一位</button>
</form>
<form action="index.php?a=edit_recommender" method="POST">
	<?php 
		$wx_name_list =  $obj->manager_recommender_list();
		$count = count( $wx_name_list );
		if ( $count > 0 ) {
			echo "<h3>已有".$count."位记录，还可增加".(20-$count)."位</h3>";
			foreach ($wx_name_list as $key => $value) {
				$name = $value['wx_name'];
				echo '<input type="text" name="'.$name.'" readonly="readonly" value="'.$name.'" id="input_'.$name.'"><input type="button" value="改名" id="edit_'.$name.'" onclick="edit_this(this.id)"><input type="button" value="删除" id="delete_'.$name.'" onclick="delete_this(this.id)"><br>';
			}
			echo "<button>保存</button>";
		}else{
			echo "<h3>已有".$count."位记录，还可增加".(10-$count)."位</h3>";
		}
	 ?>
<!-- <input type="text" name="333" readonly="readonly" value="333" id="input_333"><input type="button" name="" value="改名" id="edit_333" onclick="edit_this(this.id)"><input type="button" name="" value="删除" id="delete_333" onclick="delete_this(this.id)"> -->
</form>
</body>
<script type="text/javascript">
	function edit_this(id) {
		var input_id = "input_"+id.substr(5);
		var obj = document.getElementById(input_id);
		obj.removeAttribute("readonly");
		obj.setAttribute("value","");
		obj.setAttribute("placeholder","输入新名称后保存");
		console.log(input_id);
		console.log(obj);
	}
	function delete_this(id) {
		confirm("确认要删除吗？");
		var name = id.substr(7);
		window.top.location.href="index.php?a=delete_recommender&name="+name;
	}
</script>
</html>