<?php
	include_once('connect_db.php');
	if(@$_GET['action']=="edit"){
		$r= array();
		$editId = $_GET['editId'];
		$getDataSql = "SELECT * FROM subjects s WHERE s.id='".$editId."' LIMIT 1";
		$getDataRes = mysql_query($getDataSql,$connection);
		if($getDataRes){
			$r = mysql_fetch_assoc($getDataRes);
			if($r){
				$getDataRes = mysql_query("SELECT * from course WHERE abbrCourse = '".$r['course']."' LIMIT 1",$connection);
				$courseDetail = mysql_fetch_assoc($getDataRes);
				$r['course_details'] = $courseDetail;
			}
		}
		echo json_encode($r);
	}
	if(@$_GET['action']=="stdreport"){
		$r= array();
		$editId = $_GET['editId'];
		$getDataSql = "SELECT * FROM studentreport s WHERE s.id='".$editId."' LIMIT 1";
		$getDataRes = mysql_query($getDataSql,$connection);
		if($getDataRes){
			$r = mysql_fetch_assoc($getDataRes);
			if($r){
				echo json_encode($r);
			}
		}
		
	}
?>