<?php
include_once('connect_db.php');
include_once('functions.php');
$Error = "";
//get courseArray from database----start
		$courseArray = array();
		$courseArray = getCourse($connection,$fields=array("abbrCourse"));
//get courseArray from database----end

$sqlCondition = array();
if(@$_GET['filtercourse']){
	$sqlCondition['course'] =$_GET['filtercourse'];
}
if(@$_GET['filterSem']){
	$sqlCondition['semester'] = $_GET['filterSem'];
}
if(@$_GET['passingyear']){
	$sqlCondition['passingyear'] = $_GET['passingyear'];
}
//get report from database----start
		$rptArray = array();
		$rptArray = getReport($connection,$fields=array("*"),$sqlCondition);
//get report from database----end

/*echo "<pre>";
print_r($rptArray);*/


#########  Perform Update function ##################
	if(isset($_POST['Update'])){
		$course = $_POST['txtcourse'];
		$id = $_POST['std_id'];
		$seatno = $_POST['txtseatno'];
		$semester = $_POST['txtsemester'];
		$studentName =  $_POST['txtstdname'];
		$month = $_POST['txtmonth'];
		$year = $_POST['txtyear'];
		$passingyear = $month."-".$year;

		$subjectName = $_POST['custom_field_name'];
		$internal_marks = $_POST['custom_field_internal'];
		$external_marks = $_POST['custom_field_external'];
		
		if(empty($course)){
			echo "<script>alert('Please Enter value for all mandatory fields');</script>";
			echo "<script>window.location.href = window.location</script>";
			exit;
		}
		$obtmarksArray = array();
		for($i=0;$i<count($subjectName);$i++){
			$obtmarksArray[] = array('subject'=>$subjectName[$i],'internal'=>$internal_marks[$i],'external'=>$external_marks[$i]);
		}

		
		########  check for empty field in marks section ############

		if(CheckEmptyMarks($obtmarksArray)){
			echo "<script>alert('Blank value in Subject and marks details is not allowed')</script>";
			echo "<script>window.location.href = window.location</script>";
			exit;
		}

		#########  validate marks range ##############
		$subjects = array();
		$getSubjects = getSubjects($connection,array("subjects"),array("course"=>$course,"semester"=>$semester,"limit"=>1));
		if($getSubjects){
			$getSubjectsArray =json_decode($getSubjects[0]['subjects'],true);
			if($getSubjectsArray[0]['subject']=="")
			{
				$Error = "No subjects for the course";
			}
			else{
				$subjects = $getSubjectsArray;
			}
		}
		else{
			$Error = "No subjects defined";
		}
		if($Error){
			echo $Error;
		}else{
			$validationResult = validateMarks($subjects,$obtmarksArray,"update");

			if($validationResult==1){
				echo "<script>alert('Error: Obtained marks is greater than maximum marks');</script>";
				echo "<script>window.location.href = window.location;</script>";
				exit;
			}
		}
		$image = NULL;
		if($_FILES["txtcandidatephoto"]["name"]!=""){
			move_uploaded_file($_FILES["txtcandidatephoto"]["tmp_name"],
	 "images/" . $_FILES["txtcandidatephoto"]["name"]);
		$image= "images/".$_FILES["txtcandidatephoto"]["name"];
		}	 
		########## update data after successful validation ###############

		$updateStmt = "UPDATE studentreport SET seatNo='".$seatno."' , course='".$course."' , semester='".$semester."' , studentName='".$studentName."' , studentPhoto= '".$image."', monthYear= '".$passingyear."' , marks= '".json_encode($obtmarksArray)."' WHERE id = '".$id."' LIMIT 1";
		
		$updateRes = mysql_query($updateStmt,$connection);
		if($updateRes){
			echo "<script>alert('Data Updated successfully');</script>";
			echo "<script>window.location.href = window.location;</script>";
			exit;
		}
		else{
			echo "<script>alert('Error updating data');</script>";
			echo "<script>window.location.href = window.location;</script>";
			exit;
		}
		
	}
	
	########## perform delete ###################\
	if(isset($_GET['delid'])){
	$delSQL = "DELETE from studentreport where id=".$_GET['delid']." LIMIT 1 ";
	$deleteRes = mysql_query($delSQL,$connection);
	$msg = "Record deleted successfully";
	if($deleteRes){
		header("Location: ListReport.php?success=".$msg);
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
		$.getJSON('getdata.php',{editId:id,action:"stdreport"}, function(data) {
		//data is returned---
			$('#edit_div #std_id').val(data.id);
			$('#edit_div #txtseatno').val(data.seatNo);
			$('#edit_div #txtcourse').val(data.course);
			$('#edit_div #txtsemester').val(data.semester);
			$('#edit_div #txtstdname').val(data.studentName);
			$('#edit_div #txtcandidatephoto').val(data.studentPhoto);
			$('#edit_div #imgid').attr('src',data.studentPhoto);

			var yrArr = data.monthYear.split("-");
			$('#edit_div #txtmonth').val(yrArr[0]);
			$('#edit_div #txtyear').val(yrArr[1]);

			 var result = $.parseJSON(data.marks);
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
			window.location.href = "ListReport.php?delid="+delid;
		}
		else{
			return false;
		}
	}
	function filter(){
		var course = document.getElementById('filtercourse').value;
		var sem = document.getElementById('filterSem').value;
		var year = document.getElementById('filteryear').value;
		window.location.href = "ListReport.php?filtercourse="+course+"&filterSem="+sem+"&passingyear="+year;

	}

	function selectAll(datacount){
		if(document.getElementById("checkAll").checked==true)
		{
			document.getElementById("pid").value="";
			str="";
			for(i=0;i<datacount;i++)
			{
				document.getElementById("chkbox"+i).checked = true;
				str= str + document.getElementById("chkbox"+i).value + " ";
			}
			document.getElementById("pid").value = str;
		}
		else
		{
			document.getElementById("pid").value="";
			for(i=0;i<=datacount;i++)
			{
				document.getElementById("chkbox"+i).checked=false;
			}
		}
	}

	//function to get ID's for printing
	function storeId(obj)	{	
		var n;
		//alert ("deletevals");
		if (obj.checked==true)
		{
			var ids=obj.value;
	document.getElementById("pid").value=document.getElementById("pid").value + obj.value + " ";
		//alert (document.getElementById("delete_id").value);
		
		}
		else
		{
			var ids=obj.value;
			var str=document.getElementById("pid").value.replace(obj.value+" ","");
			document.getElementById("pid").value = str;
			
		}
		
	}

	function gotoReport(){
		var getIds = document.getElementById("pid").value;
		var resdate = document.getElementById("resdate").value;
		resdate = resdate.replace(/^\s+|\s+$/g,'');
		if(resdate==""){
			alert("Result declared date cannot be blank");
			return false;
		}
		getIds = getIds.split(" ");
		window.location.href = "getReport.php?chklist="+getIds+"&resdate="+resdate;

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
	<input type="hidden" name="pid" id="pid" value="">
	<!--Result Declared On :--> <input type="hidden" name="resdate" id="resdate" value="<?=date('d F Y')?>">

	<table width="50%" align="center" id="rounded-corner" cellspacing="0"  >
	<thead >
	<tr>
	<th class="rounded-company" colspan='6'>Filter By :

	<select name="filtercourse" id="filtercourse" onChange="filter();">
	<option value=""></option>
	<?php foreach($courseArray as $key=>$val){ ?>
	<option value="<?php echo $val['abbrCourse']?>" <?php if(@$_GET['filtercourse']==$val['abbrCourse']) echo "selected='selected'" ?>><?php echo $val['abbrCourse']?></option>
	<?php } ?>
	</select>
	&nbsp;

	<select name="filterSem" id="filterSem" style="width:150px;" onChange="filter();">
	<option value="" <?php if(@$_GET['filterSem']=="Semester-1") echo "selected='selected'" ?>></option>
	<option value="Semester-I" <?php if(@$_GET['filterSem']=="Semester-I") echo "selected='selected'" ?>>Semester-I</option>
	<option value="Semester-II" <?php if(@$_GET['filterSem']=="Semester-II") echo "selected='selected'" ?>>Semester-II</option>
	<option value="Semester-III" <?php if(@$_GET['filterSem']=="Semester-III") echo "selected='selected'" ?>>Semester-III</option>
	<option value="Semester-IV" <?php if(@$_GET['filterSem']=="Semester-IV") echo "selected='selected'" ?>>Semester-IV</option>
	<option value="Semester-V" <?php if(@$_GET['filterSem']=="Semester-V") echo "selected='selected'" ?>>Semester-V</option>
	<option value="Semester-VI" <?php if(@$_GET['filterSem']=="Semester-VI") echo "selected='selected'" ?>>Semester-VI</option>
	</select>
		
	<select name="filteryear" id="filteryear" onChange="filter()";>
		<option value=""></option>
		<?php
		for($i=2000;$i<=2020;$i++){
		?>
		<option value="<?=$i?>" <?php if(@$_GET['passingyear']==$i) echo "selected='selected'" ?>><?=$i?></option>
		<?php
		}
		?>
		</select>	
	</th>
	<th class="rounded-q4"></th>
	</tr>
	<tr>
	<th><input type="checkbox" name="checkAll" id="checkAll" value="" onChange="selectAll(<?=count($rptArray)?>);return false;"></th>
	<th>SEAT NO</th>
	<th class="rounded-q1">COURSE</th>
	<th >SEMESTER</th>
	<th >STUDENT NAME</th>
	<th class="rounded-foot">PASSING MONTH-YEAR</th>
	<th >Action</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
	<td  class="rounded-foot-left"><a href="javascript:;" class="button" onClick="gotoReport(); return false;">Print</a></td>
	<td>SEAT NO</td>
	<td class="rounded-foot" >COURSE</td>
	<td class="rounded-foot">SEMESTER</td>
	<td class="rounded-foot">STUDENT NAME</td>
	<td class="rounded-foot">PASSING MONTH-YEAR</td>
	<td class="rounded-foot-right">Action</td>
	</tr>
	</tfoot>
	<tbody>
	
	<?php
	$chkkey = 0;
	foreach($rptArray as $key=>$val){
	?>
		<tr>
		<td><input type="checkbox" name="chkbox" id="chkbox<?=$chkkey++?>" value="<?=$val['id']?>" onChange="storeId(this);return false;"></td>
		<td class="tblcell"><?=$val['seatNo']?><input type="hidden" name="edit_id" id="edit_id" value="<?=$val['id']?>"></td>
		<td class="tblcell"><?=$val['course']?></td>
		<td class="tblcell"><?=$val['semester']?></td>
		<td class="tblcell"><?=$val['studentName']?></td>
		<td class="tblcell"><?=$val['monthYear']?></td>
		<td><a href="javascript:;" onClick="DeleteRow(<?=$val['id']?>)" ><span class="delRow">Delete</span></a></td>
		</tr>
	<?php
	}
	?>

	</tbody>
	</table>
	</form>
	
	<hr width="75%">
	<br>
	<div id="entry_error"><?=@$msg?></div>
	<br>
	
	<div id="edit_div" name="edit_div" class="update_panel" >
	<form id="frmedit" name="frmedit" method="post" enctype="multipart/form-data">
	<input type="hidden" name="std_id" id="std_id">
	<table width="50%" id="edit_tbl" >
			<tr>
			<td align="left" width="160">SeatNo</td>
			<td><input type='text' name='txtseatno' id='txtseatno' style="width:220px;"></td>
			</tr>
			<tr>
			<td align="left" width="160">Course</td>
			<td>
				<select name="txtcourse" id="txtcourse">
				<option value=""></option>
				<?php foreach($courseArray as $key=>$val){ ?>
				<option value="<?php echo $val['abbrCourse']?>"><?php echo $val['abbrCourse']?></option>
				<?php } ?>
				</select>
			</td>
			</tr>
			<tr>
			<td align="left" width="160">Semester </td>
			<td ><select name="txtsemester" id="txtsemester" style="width:220px;">
			<option value="Semester-I">Semester-I</option>
			<option value="Semester-II">Semester-II</option>
			<option value="Semester-III">Semester-II</option>
			<option value="Semester-IV">Semester-IV</option>
			<option value="Semester-V">Semester-V</option>
			<option value="Semester-VI">Semester-VI</option>
			</select></td>
			</tr>
			<tr>
			<td align="left" width="160">Student Name</td>
			<td><input type='text' name='txtstdname' id='txtstdname' style="width:220px;"></td>
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
				<td>Candidate Photo : </td>
				<td><input type= "file" name="txtcandidatephoto"><img id="imgid" width="50"/></td>
				
			</tr>
		<tr><td colspan=3>	
		<br>
		<table width="50%" id="custom_field" border=1>
			<thead>
				<tr>
					<td align="left" width="200px">Subject Name</td>
					<td align="left" width="200px">Subject Internal Marks</td>
					<td align="left" width="200px">Subject External Marks</td>
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
