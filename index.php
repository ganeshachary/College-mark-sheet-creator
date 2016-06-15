<?php
include_once('connect_db.php');
include_once('functions.php');
//get courseArray from database----start
		$courseArray = array();
		$courseArray = getCourse($connection,$fields=array("abbrCourse"));
//get courseArray from database----end
$sqlCondition = "";
if(@$_GET['filtercourse']){
	$sqlCondition .=" AND course ='".$_GET['filtercourse']."' ";
}
if(@$_GET['filterSem']){
	$sqlCondition .=" AND semester = '".$_GET['filterSem']."' ";
}
//echo "Select * from subjects WHERE 1 $sqlCondition";
$getCourseSem = mysql_query("Select * from subjects WHERE 1 $sqlCondition",$connection);
$msg="";

if(isset($_GET['Save'])){
	$abbrCourse = $_GET['abbrcourse'];
	$txtcourse =  $_GET['txtcourse'];
	$semester = $_GET['txtsemester'];
	$subjectName = $_GET['custom_field_name'];
	$internal_marks = $_GET['custom_field_internal'];
	$external_marks = $_GET['custom_field_external'];
	
	if(empty($abbrCourse)){
		echo "<script>alert('Field Course(abbr)is mandatory')</script>";
		echo "<script>window.location.href='index.php'</script>";
		exit;
	}
	else if(empty($txtcourse)){
		echo "<script>alert('Field  Course Full Name is mandatory')</script>";
		echo "<script>window.location.href='index.php'</script>";
		exit;
	}
	
	$subjectArray = array();
	//echo $abbrCourse;
	//echo $txtcourse;
	for($i=0;$i<count($subjectName);$i++){
		$subjectArray[] = array('subject'=>$subjectName[$i],'internal'=>$internal_marks[$i],'external'=>$external_marks[$i]);
	}
	if(CheckEmptyMarks($subjectArray)){
		echo "<script>alert('Blank value in Subject and marks details is not allowed')</script>";
		echo "<script>window.location.href='index.php'</script>";
		exit;
	}
	$existCourse = getSubjects($connection,array("course,semester"),array('course'=>$abbrCourse,'semester'=>$semester));
	
	if(count($existCourse)==0){
		$courseStmt = "INSERT into course(abbrCourse,courseName) VALUES('".$abbrCourse."','".$txtcourse."') ON DUPLICATE KEY UPDATE courseName = '".$txtcourse."' ";
		$subjectStmt = "INSERT into subjects(course,semester,subjects) VALUES('".$abbrCourse."','".$semester."','".json_encode($subjectArray)."')";
		$insertCourseRes = mysql_query($courseStmt,$connection);
		$insertSubjectRes = mysql_query($subjectStmt,$connection);
		header("Location: index.php");
	}
	else{
		echo "<script>alert('Details already exists')</script>";
	}
}
if(isset($_GET['Update'])){
	
	$sub_id =  $_GET['sub_id'];
	$course_id =  $_GET['course_id'];
	$abbrCourse = $_GET['abbrcourse'];
	$txtcourse =  $_GET['txtcourse'];
	$semester = $_GET['txtsemester'];
	$subjectName = $_GET['custom_field_name'];
	$internal_marks = $_GET['custom_field_internal'];
	$external_marks = $_GET['custom_field_external'];
	$subjectArray = array();
	for($i=0;$i<count($subjectName);$i++){
		$subjectArray[] = array('subject'=>$subjectName[$i],'internal'=>$internal_marks[$i],'external'=>$external_marks[$i]);
	}
	$courseStmt = "UPDATE course SET abbrCourse = '".$abbrCourse."',courseName= '".$txtcourse."' WHERE id='".$course_id."'  LIMIT 1";
	$subjectStmt = "UPDATE subjects SET course= '".$abbrCourse."',semester='".$semester."',subjects ='".json_encode($subjectArray)."' WHERE id='".$sub_id."' LIMIT 1";
	$updateCourseRes = mysql_query($courseStmt,$connection);
	$updateSubjectRes = mysql_query($subjectStmt,$connection);	
	echo '<script>alert("Data Updated");</script>';
	header("Location: index.php");
}
if(isset($_GET['delid'])){
	$delSQL = "DELETE from subjects where id=".$_GET['delid']." LIMIT 1 ";
	$deleteRes = mysql_query($delSQL,$connection);
	$msg = "Record deleted successfully";
	if($deleteRes){
		header("Location: index.php?success=".$msg);
	}
}
?>

<html>
<head>
<link rel="stylesheet" href="style.css" type="text/css" />
<style type="text/css">
.tblcell{
}
.entryform
{
	background-color:#ffffff;
	color:#000066;
	float:center;
	border:2px solid;
	border-radius:15px;
}

.update_panel
{
	background-color:#ffffff;
	color:#000066;
	float:left;
	position:absolute;
	border:2px solid;
	border-radius:15px;
	display:none;
}
.delRow a:hover
{
	text-decoration: underline;
	cursor: pointer;
}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script>
function customRowRemove(element) {
	$(element).parents('tr:eq(0)').remove();
}
function customRowAdd(appendInside) {
	var row = $('#custom_field_duplicate tr').clone(true).removeAttr('id').show();
	$(appendInside).append(row);
}
	$(document).ready(function(){
	$( "#rounded-corner tr").find(".tblcell").click( function(event) {
		var id= $(this).parent().find('#edit_id').val();
		//alert(id);
		$.getJSON('getdata.php',{editId:id,action:"edit"}, function(data) {
		//data is returned---
			$('#edit_div #sub_id').val(data.id);
			$('#edit_div #course_id').val(data.course_details.id);
			$('#edit_div #abbrcourse').val(data.course);
			$('#edit_div #txtcourse').val(data.course_details.courseName);
			$('#edit_div #txtsemester').val(data.semester);
			 var result = $.parseJSON(data.subjects);
			$.each(result, function(i,row) { 
				var customField = $('#custom_field_duplicate tr').clone(true);
				$(customField).find('#custom_field_name').val(this.subject);
				$(customField).find('#custom_field_internal').val(this.internal);
				$(customField).find('#custom_field_external').val(this.external);
				if(i == 0) {
					$(customField).find('.row_remove').html('<input type="button" value="+" onclick="customRowAdd(\'#update_custom_field\')" style="width: 35px;"/>');
					$('#update_custom_field').html(customField);
				} else {
					$('#update_custom_field').append(customField);
				}
			});

		});

		$('#edit_div').show();
		$("#edit_div").css( {position:"absolute", top:event.pageY, left: event.pageX});
		
	});
	});

	$(document).ready(function(){
	$( "#Cancel").click( function(event) {
		$('#edit_div').hide();
	});
	});

	function DeleteRow(delid){
		var res = confirm("Are you sure you want to delete this row?"); 
		if(res==true){
			window.location.href = "index.php?delid="+delid;
		}
		else{
			return false;
		}
	}
	function filter(){
		var course = document.getElementById('filtercourse').value;
		var sem = document.getElementById('filterSem').value;
		window.location.href = "index.php?filtercourse="+course+"&filterSem="+sem;

	}

</script>
</head>
<body>
<a href="index.php" class="button">Index Page </a>
<a href="input_form.php" class="button">Get Student Report </a>
<a href="ListReport.php" class="button">List Report </a>

<br>
<br>

<div>
<table width="100%" id="custom_field_duplicate" style="display: none;">
		<tr>
			<td>
				<input name="custom_field_name[]" id="custom_field_name" type="text" style="font-weight: bold; width: 200px;"/>
			</td>
			<td>
				<input name="custom_field_internal[]" id="custom_field_internal"   type="text" style="width: 200px;"/>
			</td>
			<td>
				<input type="text"  id="custom_field_external" style="width:200px" name="custom_field_external[]"  />
			</td>
			<td class="row_remove"><input type="button" value="-" onClick="customRowRemove(this)" style="width: 35px;"/></td>
		</tr>
	</table>
	<div>
	<form method="get" name="boxfrm">
	<table width="50%" align="center" id="rounded-corner" cellspacing="0"  >
	<thead >
	<tr>
	<th class="rounded-company" colspan='2'>Filter By :

	<select name="filtercourse" id="filtercourse" onChange="filter();">
	<option value=""></option>
	<?php foreach($courseArray as $key=>$val){ ?>
	<option value="<?php echo $val['abbrCourse']?>" <?php if(@$_GET['filtercourse']==$val['abbrCourse']) echo "selected='selected'" ?>><?php echo $val['abbrCourse']?></option>
	<?php } ?>
	</select>
	&nbsp;

	<select name="filterSem" id="filterSem" style="width:150px;" onChange="filter();">
	<option value="" <?php if(@$_GET['filterSem']=="Semester-I") echo "selected='selected'" ?>></option>
	<option value="Semester-I" <?php if(@$_GET['filterSem']=="Semester-I") echo "selected='selected'" ?>>Semester-I</option>
	<option value="Semester-II" <?php if(@$_GET['filterSem']=="Semester-II") echo "selected='selected'" ?>>Semester-II</option>
	<option value="Semester-III" <?php if(@$_GET['filterSem']=="Semester-III") echo "selected='selected'" ?>>Semester-III</option>
	<option value="Semester-IV" <?php if(@$_GET['filterSem']=="Semester-IV") echo "selected='selected'" ?>>Semester-IV</option>
	<option value="Semester-V" <?php if(@$_GET['filterSem']=="Semester-V") echo "selected='selected'" ?>>Semester-V</option>
	<option value="Semester-VI" <?php if(@$_GET['filterSem']=="Semester-VI") echo "selected='selected'" ?>>Semester-VI</option>
	</select>

	</th>
	<th class="rounded-q4"></th>
	</tr>
	<tr>
	<th >Course</th>
	<th class="rounded-q1">Semester</th>
	<th>Action</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
	<td class="rounded-foot-left" >Course</td>
	<td class="rounded-foot">Semester</td>
	<td class="rounded-foot-right">Action</td>
	</tr>
	</tfoot>
	<tbody>
	
	<?php
	while($getRow = mysql_fetch_assoc($getCourseSem)){
	
	?>
		<tr>
		<td class="tblcell"><?=$getRow['course']?><input type="hidden" name="edit_id" id="edit_id" value="<?=$getRow['id']?>"></td>
		<td class="tblcell"><?=$getRow['semester']?></td>
		<td><a href="javascript:;" onClick="DeleteRow(<?=$getRow['id']?>)" ><span class="delRow">Delete</span></a></td>
		</tr>
	<?php
	}
	?>

	</tbody>
	</table>
	</form>
	
	<hr width="75%">
	<br>
	<div id="entry_error"><?=$msg?></div>
	<br>
	<form  name="entry_form" id="entry_form" method="get" >		
					
				<table width="75%" align="center" class="entryform" cellpadding="15px">
					<tr>
					<td align="left" width="160">New Course(abbr)</td>
					<td><input type='text' name='abbrcourse' id='abbrcourse'></td>
					</tr>
					<tr>
					<td align="left" width="160">Course Full Form</td>
					<td><input type='text' name='txtcourse' id='txtcourse'></td>
					</tr>
					<tr>
					<td align="left" width="160">Semester </td>
					<td ><select name="txtsemester" id="txtsemester" style="width:150px;">
					<option value="Semester-I">Semester-I</option>
					<option value="Semester-II">Semester-II</option>
					<option value="Semester-III">Semester-III</option>
					<option value="Semester-IV">Semester-IV</option>
					<option value="Semester-V">Semester-V</option>
					<option value="Semester-VI">Semester-VI</option>
					</select></td>
					</tr>
					
				<tr><td colspan=3>	
				<br>
				<table width="50%" id="custom_field" border=1>
					<thead>
						<tr>
							<td align="left" width="200px">Subject Name</td>
							<td align="left" width="200px">Subject Max Internal Marks</td>
							<td align="left" width="200px">Subject Max External Marks</td>
						</tr>
					</thead>
					<tbody id="save_custom_field" >
						<tr>
							<td>
								<input name="custom_field_name[]" type="text" id="custom_field_name" style="font-weight: bold; width: 200px;"/>
							</td>
							<td>
								<input name="custom_field_internal[]" id="custom_field_internal"  type="text" style="width: 200px;"/>
							</td>
							<td>
						 	<?php ?>
							<input name="custom_field_external[]" type="text" id="custom_field_external" style="width:200px"    />
                        	</td>
							<td>
								<input type="button" value="+" onClick="customRowAdd('#save_custom_field')" style="width: 35px;"/>
							</td>
						</tr>
						
					</tbody>
				</table>
				<br>
				<input type="submit" value="Save" name="Save">
				<br>
	</form>
	<div id="edit_div" name="edit_div" class="update_panel">
	<form id="frmedit" name="frmedit">
	<input type="hidden" name="sub_id" id="sub_id">
	<input type="hidden" name="course_id" id="course_id">
	<table width="50%" id="edit_tbl" >
			<tr>
			<td align="left" width="160">New Course(abbr)</td>
			<td><input type='text' name='abbrcourse' id='abbrcourse' style="width:220px;"></td>
			</tr>
			<tr>
			<td align="left" width="160">Course Full Form</td>
			<td><input type='text' name='txtcourse' id='txtcourse' style="width:220px;"></td>
			</tr>
			<tr>
			<td align="left" width="160">Semester </td>
			<td ><select name="txtsemester" id="txtsemester" style="width:220px;">
			<option value="Semester-I">Semester-I</option>
			<option value="Semester-II">Semester-II</option>
			<option value="Semester-III">Semester-III</option>
			<option value="Semester-IV">Semester-IV</option>
			<option value="Semester-V">Semester-V</option>
			<option value="Semester-VI">Semester-VI</option>
			</select></td>
			</tr>
			
		<tr><td colspan=3>	
		<br>
		<table width="50%" id="custom_field" border=1>
			<thead>
				<tr>
					<td align="left" width="200px">Subject Name</td>
					<td align="left" width="200px">Subject Max Internal Marks</td>
					<td align="left" width="200px">Subject Max External Marks</td>
				</tr>
			</thead>
			<tbody id="update_custom_field" >
				
				
			</tbody>
	</table>
				<br>
				<input type="submit" value="Update" name="Update">
				<input type="button" value="Cancel" name="Cancel" id="Cancel">
				<br>
	</form>
	</div>
	</body>
	</html>
