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
<title>添加/修改WLAN组配置</title>
</head>
<?php

//修改操作
if(isset($_POST["edit_id"]) && $_POST["edit_id"]!=""){
        $dbhelper = new DAL();
        $wlan_group_name = $dbhelper->getall("select id,wlan_group_name from wlan_group where id !=".$_POST["edit_id"]);
        foreach($wlan_group_name as $name){
        		if($name["wlan_group_name"] == $_POST["edit_wlan_group_name"]){
        				echo "<script>alert('名称已存在');location='wlan_group.php?r='+Math.random();</script>";
        			}
        	}
        $update = $dbhelper->update("update wlan_group set wlan_group_name='".trim($_POST["edit_wlan_group_name"])."' where id=".$_POST["edit_id"]);
   	    if($update>0){
		  echo "<script>alert('修改成功');location='wlan_group.php?r='+Math.random();</script>";
    	}
    }else if(isset($_POST["edit_wlan_group_name"]) && $_POST["edit_wlan_group_name"]!=""){
        $dbhelper = new DAL();
        $wlan_group_name = $dbhelper->getall("select wlan_group_name from wlan_group");
        foreach($wlan_group_name as $name){
        		if($name["wlan_group_name"] == $_POST["edit_wlan_group_name"]){
        				echo "<script>alert('名称已存在');location='wlan_group.php?r='+Math.random();</script>";
        			}
        	}
        $insert = $dbhelper->insert("insert into wlan_group values(null,'".trim($_POST["edit_wlan_group_name"])."')");
   	    if($insert>0){
		  echo "<script>alert('添加成功');location='wlan_group.php?r='+Math.random();</script>";
    	}
    }

?>

<script type="text/javascript"> 
</script>    
</head>
<body></body>
</html>