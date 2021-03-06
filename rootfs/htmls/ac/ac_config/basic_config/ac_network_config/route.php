﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
define("PATH", "../../../../");
include PATH."db/dbhelper.php";
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
<title>添加静态路由</title>
</head>
<?php
    if(isset($_GET["action"])){
        exec('cd /ac/script && ./init_route_scr',$arr,$retval);
        if($retval == -1 || $retval == 255){
            echo "<script>alert('应用失败!请检查配置!');location='ac_network_config.php?r='+Math.random();</script>";
        }else{
            echo "<script>alert('应用成功!');location='ac_network_config.php?r='+Math.random();</script>";
        }
    }
?>
<style type="text/css">
<!--
.acinfo_table{
	background:#a8c7ce;
	width:50%;
	border: 1px solid #ddeeff;
}
.tdContent{
	background-color:#ffffff;
	padding:5px;
}
.tdContentF9{
	background-color:#f9f9f9;
	padding:5px;
}
tr.over td{
    background-color:#d5f4fe;
}
-->
</style>

<script type="text/javascript">
    $(document).ready(function(){
        $("#route").validate({
			rules:{
				"add_source_ip":{
					required:true
				},
                "add_destination_ip":{
					required:true
				}
			}
		});
        $("#tb_route tr").mouseover(function(){
    	   $(this).addClass("over");
    	});
    	$("#tb_route tr").mouseout(function(){
    	   $(this).removeClass("over");
    	});
    });
</script>

<?php
if(isset($_GET["id"])){
    $dbhelper = new DAL();
    $delete = $dbhelper->delete("delete from route where id=".$_GET["id"]);
    if($delete > 0){
        echo "<script>alert('删除成功!');location='ac_network_config.php?r='+Math.random();</script>";
    }
}
if(isset($_POST["add_source_ip"])){
    $dbhelper = new DAL();
    $params = array($_POST["add_source_ip"],$_POST["add_destination_ip"]);
    $sql = "insert into route(source_ip,destination_ip) values(?,?)";
    $insert = $dbhelper->insert($sql,$params);
    if($insert > 0){
        echo "<script>alert('添加成功!');location='ac_network_config.php?r='+Math.random();</script>";
    }
}
?>
<body>
<div class="title">添加静态路由</div>
<br />
<form id="route" name="route" action="route.php" method="POST">
<table align="center" class="acinfo_table" id="tb_route" cellpadding="1px" cellspacing="1px">
    <tr>
        <td class="tdContent" width="40%">目的IP/掩码：</td>
        <td class="tdContent" align="left"><input type="text" name="add_source_ip" id="add_source_ip" /></td>
    </tr>
    <tr>
        <td class="tdContentF9">下一跳IP：</td>
        <td class="tdContentF9" align="left"><input type="text" name="add_destination_ip" id="add_destination_ip" /></td>
    </tr>
 </table>
 <div style=" padding:5px 25%;">
 <input class="bt" type="submit" value="确定" />&nbsp;&nbsp;
<input class="bt" type="reset" value="重置"/>&nbsp;&nbsp;
 <input class="bt" type="button" onclick="location='ac_network_config.php'" value="返回"/> 
 </div>
</form>
</body>
</html>