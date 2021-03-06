<?php
include_once('connect_db.php');
include_once('functions.php');

		
		$getSem = "Semester-I";
		$getcourse = "";
		$subjects = array();
		if(isset($_GET['txtsemester'])){
			$getSem = $_GET['txtsemester'];
		}
		if(isset($_GET['txtcourse'])){
			$getcourse= $_GET['txtcourse'];
		}

		//get courseArray from database----start
		$courseArray = getCourse($connection,$fields=array("abbrCourse"));
		//get courseArray from database----end

		// get subjects for the selected semester and course from database --- start
		$getSubjects = getSubjects($connection,array("subjects"),array("course"=>$getcourse,"semester"=>$getSem,"limit"=>1));
		if($getSubjects){
			$getSubjectsArray = $getSubjects[0]['subjects'];
			$getSubjectsArray =json_decode($getSubjectsArray,true);
			if($getSubjectsArray[0]['subject']=="")
			{
				$Error = "No subjects defined";
			}
			else{
				$subjects = $getSubjectsArray;
			}
		}
		else{
			$Error = "No subjects defined";
		}
		// get subjects for the selected semester and course from database --- end
?>



<html>
<head>
<link rel="stylesheet" href="style.css" type="text/css" />
<script type="text/javascript">
function getfileformat(obj){
	obj.value = "getcsv";
	document.frmmarks.submit();
}
</script>
</head>
<body>
<a href="index.php" class="button">Index Page </a>
<a href="input_form.php" class="button">Get Student Report </a>
<a href="ListReport.php" class="button">List Report </a>

<br>
<h3> Enter Student Information </h3>
<div >
<!-- div for getting report of student that already exists in database ------  start -->
<div width="50%" style="display: inline-block;">
<form name="frmexists" id="frmexists" method="post" action="getReport.php">
  <fieldset>
    <legend>Enter Information</legend>
	<table>
    <tr>
	<td>Course:</td>
	<td><select name="txtcourse" id="txtcourse">
<option value=""></option>
<?php foreach($courseArray as $key=>$val){ ?>
<option value="<?php echo $val['abbrCourse']?>" <?php if($getcourse==$val['abbrCourse']) echo "selected='selected'" ?>><?php echo $val['abbrCourse']?></option>
<?php } ?>
</select></td>
	</tr>
	<tr>
	<td>Seat No: </td>
	<td><input type="text" name="txtseatno"></td>
	</tr>
	<tr>
    <td>Semester: </td>
	<td><select name="txtsemester" >
<option value="Semester-I" <?php if($getSem=="Semester-I") echo "selected='selected'" ?>>Semester-I</option>
<option value="Semester-II" <?php if($getSem=="Semester-II") echo "selected='selected'" ?>>Semester-II</option>
<option value="Semester-III" <?php if($getSem=="Semester-III") echo "selected='selected'" ?>>Semester-III</option>
<option value="Semester-IV" <?php if($getSem=="Semester-IV") echo "selected='selected'" ?>>Semester-IV</option>
<option value="Semester-V" <?php if($getSem=="Semester-V") echo "selected='selected'" ?>>Semester-V</option>
<option value="Semester-VI" <?php if($getSem=="Semester-VI") echo "selected='selected'" ?>>Semester-VI</option>
</select>
	</td>
	</tr>
	<tr>
	<td>Passing month-year :</td>
	<td><select name="txtmonth" id="txtmonth">
<option value=""></option>
<option value="January">Jan</option>
<option value="February">Feb</option>
<option value="March">Mar</option>
<option value="April">Apr</option>
<option value="May">May</option>
<option value="June">Jun</option>
<option value="July">Jul</option>
<option value="August">Aug</option>
<option value="September">Sep</option>
<option value="October">Oct</option>
<option value="November">Nov</option>
<option value="December">Dec</option>
</select>

<select name="txtyear" id="txtyear">
<option value=""></option>
<?php
for($i=2000;$i<=2020;$i++){
?>
<option value="<?=$i?>"><?=$i?></option>
<?php
}
?>
<?php
if($getSem=="Semester-I")
	{
		$zz="14th January 2013";
	}
else if($getSem=="Semester-II")
	{
		$zz="3rd July 2013";
	}
?>
</select>
	</td>
	
	</tr>
	<tr>
	<td><!--Result Declared On : --></td>
	<td><input type="hidden" name="resdate" id="resdate" value="<? echo $zz;?>"></td>
	</tr>
	<tr>
	<td colspan=2><input type="submit" name="getReport" value="Get Report"><td>
	</table>
  </fieldset>
</form>
</div>
<!-- div for getting report of student that already exists in database ------  start -->
<hr width="100%">


<h3>New Record </h3>
<div align = "left" style="border:2px solid;
	border-radius:15px;display: table;margin: 10px;">
<form name="frmmarks" method="post"  action="getReport.php" enctype="multipart/form-data"><br>
<table style="margin-left: 20px;margin-right: 20px;">
<tr>
<td>Semester  : </td>
<td><select name="txtsemester" id="txtsemester" onChange="window.location='input_form.php?txtsemester='+this.value+'&txtcourse=<?=$getcourse?>'">
<option value="Semester-I" <?php if($getSem=="Semester-I") echo "selected='selected'" ?>>Semester-I</option>
<option value="Semester-II" <?php if($getSem=="Semester-II") echo "selected='selected'" ?>>Semester-II</option>
<option value="Semester-III" <?php if($getSem=="Semester-III") echo "selected='selected'" ?>>Semester-III</option>
<option value="Semester-IV" <?php if($getSem=="Semester-IV") echo "selected='selected'" ?>>Semester-IV</option>
<option value="Semester-V" <?php if($getSem=="Semester-V") echo "selected='selected'" ?>>Semester-V</option>
<option value="Semester-VI" <?php if($getSem=="Semester-VI") echo "selected='selected'" ?>>Semester-VI</option>
</select>
</td>
</tr>
<tr>
<td>Course : </td>
<td><select name="txtcourse" id="txtcourse" onChange="window.location='input_form.php?&txtsemester=<?=$getSem?>&txtcourse='+this.value">
<option value=""></option>
<?php foreach($courseArray as $key=>$val){ ?>
<option value="<?php echo $val['abbrCourse']?>" <?php if($getcourse==$val['abbrCourse']) echo "selected='selected'" ?>><?php echo $val['abbrCourse']?></option>
<?php } ?>
</select></td>
</tr>


<tr>
	<td>Passing month-year :</td>
	<td><select name="txtmonth" id="txtmonth">
		<option value=""></option>
			<option value="January">Jan</option>
			<option value="February">Feb</option>
			<option value="March">Mar</option>
			<option value="April">Apr</option>
			<option value="May">May</option>
			<option value="June">Jun</option>
			<option value="July">Jul</option>
			<option value="August">Aug</option>
			<option value="September">Sep</option>
			<option value="October">Oct</option>
			<option value="November">Nov</option>
			<option value="December">Dec</option>
		</select>

		<select name="txtyear" id="txtyear">
		<option value=""></option>
		<?php
		for($i=2000;$i<=2020;$i++){
		?>
		<option value="<?=$i?>"><?=$i?></option>
		<?php
		}
		?>
		</select>
	</td>
</tr>

<tr>
<td colspan="2"><hr></td>
</tr>

<tr style="border-bottom:solid 1px black;">
	<td><button id="getcsv" name="getcsv" value="noCSV" onClick="getfileformat(this)">Get CSV file format</button></td>
</tr>

<tr style="border-bottom:solid 1px black;">
	<td>Upload marks File</td>
	<td><input type= "file" name="txtmarksfile"></td>
</tr>
<tr>
<td colspan="2"><hr></td>
</tr>

<tr>
	<td>Seat No : </td>
	<td> <input type= "text" name="txtseatno"></td>
</tr>

<tr>
	<td>Candidate Name : </td>
	<td><input type= "text" name="txtcandidatename"></td>
</tr>

<tr>
	<td>Candidate Photo : </td>
	<td><input type= "file" name="txtcandidatephoto"></td>
</tr>

</table>
<hr width="100%" align="left">
<?php

if(empty($subjects)){
		echo "No subjects defined for the selected criteria ";
		exit;
	}

?>
<table  style="margin-left: 20px;margin-right: 20px;">
<tr>
<th>Course in Semesters</th>
<th>Internal marks</th>
<th>External marks</th>
</tr>
<?php
foreach($subjects as $val){
?>
	<tr>
	<td><?=$val['subject']?></td>
	<td><input type="text" name="txtobtinternal[]" ></td>
	<td><input type="text" name="txtobtexternal[]"></td>
	</tr>
<?php
}

?>
<tr>
<td>
<input type="submit" value="submit" name="submit" >
</td>
</tr>
</table>
</form>
</div>
</div>
</body>
</html>