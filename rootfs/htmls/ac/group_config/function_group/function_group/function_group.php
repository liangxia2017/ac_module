﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
define("PATH", "../../../../");
include PATH."db/dbhelper.php";
include PATH."db/page.php";
?>
<head>
<META   HTTP-EQUIV="pragma"   CONTENT="no-cache">         
<META   HTTP-EQUIV="Cache-Control"   CONTENT="no-cache,   must-revalidate">         
<META   HTTP-EQUIV="expires"   CONTENT="0"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="<?php echo PATH ?>js/jquery.js"></script>
<script src="<?php echo PATH ?>js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo PATH ?>js/checkbox.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo PATH ?>css/body.css" type="text/css" />
<title>功能分组</title>
</head>

<style type="text/css">
<!--
.acinfo_table{
	background:#a8c7ce;
/*	width:100%;*/
/*	border: 1px solid #ddeeff;*/
}

.tdHeader{
	background-color:#d3eaef;
/*	padding:5px; */
}

.tdContent{
	background-color:#ffffff;
	padding:5px;
	text-align:center;
}

.tdContentF9{
	background-color:#f9f9f9;
	padding:5px;
	text-align:center;
}

.add{
    background-color:#F3F8F7;
    padding:5px;
	text-align:right;
    color:#73938E;
    font-weight:bold;
}

tr.over td{
    background-color:#d5f4fe;
}

-->
</style>

<script type="text/javascript">
	$(document).ready(function(){
	$("#stripe tr").mouseover(function(){
	$(this).addClass("over");
	});
	$("#stripe tr").mouseout(function(){
	$(this).removeClass("over");
	});
	});
</script>

<?php
//查询操作
$selectSql='select * from function_group';
$countSql='select count(*) from function_group';
$page = new Page($selectSql,$_POST["pageNow"],$countSql);
?>


<?php

if(isset($_GET["id"])|isset($_POST["id"])){
    if(isset($_GET["action"])){
	   $dbhelper = new DAL();	
	   $getRecord = $dbhelper->getRow("select * from function_group where id =".$_GET['id']);
    }
    else{
	   $dbhelper = new DAL();
	   $group_id="0";	
	   if(isset($_GET["id"])){
	       $group_id=$group_id.",".$_GET["id"];
           }else{
		foreach($_POST["id"] as $v){
			$group_id=$group_id.",".$v;
		  }
	   }
	   $delete = $dbhelper->delete("delete from function_group where id in (".$group_id.")");
    if($delete>0){
		echo "<script>alert('删除成功');location='function_group.php?r='+Math.random();</script>";
	   }
    }
}
?>

<script type="text/javascript"> 
$(document).ready(function(){
    $("#edit_id").val("<?php echo $getRecord->id;?>");
    $("#edit_function_group_name").val("<?php echo $getRecord->function_group_name;?>");
    $("#edit_function_group").validate({
			rules:{
				"edit_function_group_name":{
					required:true
				}
			}
		});
});	
</script>


</head>
<body>
<!--<div class="title">功能分组</div>-->

<form name="edit_function_group" id="edit_function_group" action="edit_function_group.php" method="post">
    <input type="hidden" name="edit_id" id="edit_id" value=""/>
<table cellpadding="0px" cellspacing="0px" width="50%">
<input type="hidden" name="edit_id" id="edit_id" value=""/>
    <tr>
        <td class="add">功能分组名称:</td>
        <td align="left"><input type="text" name="edit_function_group_name" id="edit_function_group_name"/></td>
    </tr>
    <tr>
        <td colspan="2"><input class="bt" type="submit" value="注册" />&nbsp;&nbsp;<input class="bt" type="reset" value="重置"/></td>
    </tr>
</table>
</form>
<hr />
<form name="function_group" id="function_group" method="post">
	<table align="center" width="90%">
      <tr>
      <td colspan="2">
        <table class="acinfo_table" width="100%" id="stripe" cellpadding="1px" cellspacing="1px">
        <tr>
        <td class="tdHeader"><input type="checkbox"	onclick="select_all(this,'id[]')" /></td>
        <td class="tdHeader">序号</td>
        <td class="tdHeader">功能分组名称</td>
        <td class="tdHeader">操作</td>
      </tr>
      <?php
			$result = $page->getResult();
			$i=1;
			foreach ($result as $rs){
				$rs = (array)$rs;
				if($i%2 == 0){
	   ?>
      <tr>
        <td class="tdContent"><input type="checkbox" name="id[]" id="id" value="<?php echo $rs["id"];?>" />
		</td>
        <td class="tdContent"><?php echo $i;?>
        </td>
        <td class="tdContent"><a href="../func_config/func_config.php?group_name=<?php echo $rs["function_group_name"];?>"><?php echo $rs["function_group_name"];?></a>
        </td>
        <td class="tdContent">
        <a href="#" onclick="this.href='function_group.php?id=<?php echo $rs["id"];?>&action=modify'">
				修改</a> &nbsp;|&nbsp;<a href="#"
					onclick="if(confirm('您确定删除？')) this.href='function_group.php?id=<?php echo $rs["id"];?>'">删除</a>
        </td>
      </tr>
      <?php 
        }else{
      ?>
      <tr>
        <td class="tdContentF9"><input type="checkbox" name="id[]" id="id" value="<?php echo $rs["id"];?>" />
		</td>
        <td class="tdContentF9"><?php echo $i;?>
        </td>
        <td class="tdContentF9"><a href="../func_config/func_config.php?group_name=<?php echo $rs["function_group_name"];?>"><?php echo $rs["function_group_name"];?></a>
        </td>
        <td class="tdContentF9">
        <a href="#" onclick="this.href='function_group.php?id=<?php echo $rs["id"];?>&action=modify'">
				修改</a> &nbsp;|&nbsp;<a href="#"
					onclick="if(confirm('您确定删除？')) this.href='function_group.php?id=<?php echo $rs["id"];?>'">删除</a>
        </td>
      </tr>
        <?php
        }
            $i++;	 
			}
            
	?>
    </table>
    </td>
    </tr>
    <tr>
		<td align="left">
                <input class="bt" type="button"	onclick="delete_all(this,'id[]','function_group.php')" value="删除" />
		</td>
		<td  align="right" width="85%"><?php $formId='function_group';include PATH.'db/pageTemplate.php';?>				
		</td>
	</tr>
</table>

</form>
</body>
</html>