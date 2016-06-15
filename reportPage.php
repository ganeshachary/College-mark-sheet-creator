<?php

switch($semester)
{
	case "Semester-I":
	$sem = "FIRST SEMESTER";
	$yr = "FIRST YEAR";
	break;

	case "Semester-II":
	$sem = "SECOND SEMESTER";
	$yr = "FIRST YEAR";
	break;

	case "Semester-III":
	$sem = "THIRD SEMESTER";
	$yr = "SECOND YEAR";
	break;

	case "Semester-IV":
	$sem = "FOURTH SEMESTER";
	$yr = "SECOND YEAR";
	break;

	case "Semester-V":
	$sem = "FIFTH SEMESTER";
	$yr = "THIRD YEAR";
	break;

	case "Semester-VI":
	$sem = "SIXTH SEMESTER";
	$yr = "THIRD YEAR";
	break;
}

$titleText = "<font size=\"2px\"> <br> 
			 <br>
			 <br><br>
			 <br></font>
			".$course." ".$yr." ".$sem." EXAMINATION <br>
			GRADE CARD" ;
$passfail="Passes";

if($sem=="FIRST SEMESTER")
	{
		$z="28th January 2014";
	}
else if($sem=="SECOND SEMESTER")
	{
		$z="3rd July 2014";
	}

//print_r($obtinternal);
//print_r($obtexternal);

?>
<html>
<head>
<style type='text/css'>
.left img{ width: 30%; height: 15%; float:left; text-align:left; display:inline; }
.right img{ width: 20%;  height: 10%; float:right; text-align:left; display:inline; }
.center img{ width: 30%; height: 15%; float:center; text-align:left; display:inline; }
.right img{ width: 20%;  height: 10%; float:right; text-align:left; display:inline; }
.container { width: 80%; margin:auto; float: center;}
@media print
{
.page-break {page-break-after:always}
}
</style>
<script>
function setColValue(val)
{
	document.getElementById('tbl_cell').innerHTML=val;
}
</script>
</head>
<body>
<table width="100%" align="center" style="font-size:11px">
<tr>
<!--<td align="left" width="15%"><img src="university.jpg" width="100%" height="100%"></td>-->
<td align="center"><?php echo $titleText?></td>
<!--<td align="right"  width="12%"><img src="siescoms.jpg"  width="100%" height="100%"></td>-->
</tr>
</table>

<table width="100%" align="center" style="font-size:11px">
<tr>
<td align="left" width="16%">Programme : <b><font size="2%"> <?=$programme?></font></b> </td>
<td colspan="2"><b><font size="2%"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	<?=$semester?></font></b></td>
<td width="9%"  rowspan="2" align="right"><img src="<?=$val['studentPhoto']?>" width="60%" height="25%"></td>
</tr>
<tr>
<td width="5%" align="center">Examination Seat No<br><b><font size="2%"><?=$seatno?></font></b></td>
<td width="20%" align="center">Name of the Candidate<br><b><font size="2%"><?=$candidatename?></font></b></td>
<td width="20%" align="center">Month and Year of Passing the Examination<br><b><font size="2%"><?=$passingyear?></font></b></td>
</tr>
</table>

<table  width="100%" border="1" align="center" style="font-size:11px" id="tbl_marks">
<tr>
<th>Courses in Semester</th>
<th colspan="2">Internal Assessment </th>
<th colspan="2">Semester End Examination</th>
<th colspan="2">Total Marks</th>
<th rowspan="2">Grade</th>
<th rowspan="2">Grade Points (G)</th>
<th rowspan="2">Credits Per Course (C)</th>
<th rowspan="2">&#8721;CG=C*G</th>
<th rowspan="2">SGPA=&#8721;(CG)/&#8721;(C)</th>
</tr>
<tr>
<td>&nbsp;</td>
<td>Maximum Marks</td>
<td>Marks Obtained</td>
<td>Maximum Marks</td>
<td>Marks Obtained</td>
<td>Maximum Marks</td>
<td>Marks Obtained</td>
</tr>
<tr id="tbl_row">
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align="center">(A)</td>
<td>&nbsp;</td>
<td align="center">(B)</td>
<td>&nbsp;</td>
<td align="center">(A+B)</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td rowspan = "<?=count($subjects)+2?>" align="center" id="tbl_cell<?=$key?>"></td>
</tr>
<?php
$i=-1;
$sumCreditsPerCourse = 0;
$sumofProduct = 0;
foreach($subjects as $val)
{
		$i++;
?>
<tr>
<td width="20%"><?=$val['subject']?></td>
<td align="center"><?=$val['internal']?></td>
<td align="center"><?=@$obtinternal[$i]?></td>
<td align="center"><?=$val['external']?></td>
<td align="center"><?=@$obtexternal[$i]?></td>
<td align="center"><?php $overallTotal = $val['internal']+$val['external']; echo $overallTotal;?></td>
<td align="center"><?php $totalMarks = @$obtinternal[$i]+@$obtexternal[$i]; echo $totalMarks; ?></td>
<td align="center"><?php 
	$percentTotalMarks = round(($totalMarks/$overallTotal)*100,2);
	$creditsPerCourse = round($val['external']/30);
if($percentTotalMarks >= 75){
	$grade = "O";
	$gradePoints = 7;
	//$creditsPerCourse = 2;
}else if($percentTotalMarks >= 70 && $percentTotalMarks <= 74.99 ){
	$grade = "A";
	$gradePoints = 6;
	//$creditsPerCourse = 1;
}else if($percentTotalMarks >= 65 && $percentTotalMarks <= 69.99 ){
	$grade = "B";
	$gradePoints = 5;
	///$creditsPerCourse = 1;
}else if($percentTotalMarks >= 60 && $percentTotalMarks <= 64.99 ){
	$grade = "C";
	$gradePoints = 4;
	//$creditsPerCourse = 2;
}else if($percentTotalMarks >= 55 && $percentTotalMarks <= 59.99 ){
	$grade = "D";
	$gradePoints = 3;
	//$creditsPerCourse = 2;
}else if($percentTotalMarks >= 50 && $percentTotalMarks <= 54.99 ){
	$grade = "E";
	$gradePoints = 2;
	//$creditsPerCourse = 2;
}else if($percentTotalMarks <= 49.99 ){
	$grade = "F";
	$gradePoints = 1;
	//$creditsPerCourse = 2;
	$passfail="Fails";
}
echo $grade;
?></td>
<td align="center"><?=$gradePoints?></td>
<td align="center"><?php echo $creditsPerCourse; $sumCreditsPerCourse+=$creditsPerCourse?></td>

<td align="center"><?php $product = $creditsPerCourse*$gradePoints;
echo $product;
$sumofProduct+=$product;
?></td>
</tr>
<?php

}
?>
<tr>
<td>Total</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align="center">&#8721;(C)= <?=$sumCreditsPerCourse?></td>
<td align="center">&#8721;(CG)=  <?=$sumofProduct?></td>
</tr>

<tr>
<td colspan="4" align="center" id="credits<?=$key?>">Credits Earned =</td>
<td colspan="2" align="center" id="sgpa<?=$key?>">SGPA=</td>
<td colspan="3"></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td rowspan="2" id="finalGrade<?=$key?>" align="center"></td>
</tr>

<tr>
<td colspan="9" align="center" id="passfail<?=$key?>"></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
<?php
$finalRow = $sumofProduct/$sumCreditsPerCourse;
if($finalRow>=6.5 && $finalRow<=7)
$finalGrade = "O";

else if($finalRow>=5.5 && $finalRow<=6.49)
$finalGrade = "A";

else if($finalRow>=4.5 && $finalRow<=5.49)
$finalGrade = "B";

else if($finalRow>=3.5 && $finalRow<=4.49)
$finalGrade = "C";

else if($finalRow>=2.5 && $finalRow<=3.49)
$finalGrade = "D";

else if($finalRow>=2 && $finalRow<=2.49)
$finalGrade = "E";

else if($finalRow<2)
$finalGrade = "F";

$str = "<script>document.getElementById('tbl_cell".$key."').innerHTML='".round($finalRow,1)."';
document.getElementById('sgpa".$key."').innerHTML='SGPA = ".round($finalRow,1)."';
document.getElementById('finalGrade".$key."').innerHTML='Grade ".$finalGrade."';
document.getElementById('credits".$key."').innerHTML='Credits Earned = ".$sumCreditsPerCourse."';
document.getElementById('passfail".$key."').innerHTML='".$passfail."';
</script>";
echo $str;
?>
<table  width="100%" align="center" cellpadding="16px" style="font-size:11px">
<tr>
<td colspan="3">Note: A student will be declared as Pass, if he secures minimum 50% marks in the Internal Assessment and the Semester End Examination Separately</td>
</tr>
<tr>
<td align="left">Result Declared on <?php echo $z;?></td>
<td>Chairperson(Examination)</td>
<td align="right">Director</td>
</tr>
</table>
<div class="page-break"></div>
</body>
</html>
