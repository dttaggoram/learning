<?php include('logcheckteacher.php');?>

<?php

$cid = "-1";
if (isset($_GET['cid'])) {
  $cid = $_GET['cid'];
}

$pid = "-1";
if (isset($_GET['pid'])) {
  $pid = $_GET['pid'];
}

$query_outstanding = sprintf("SELECT Student.user,papername,mark,PreSurvey.studentid AS 'presurvey' ,PostSurvey.studentid AS 'postsurvey' 
  FROM Papers 
  INNER JOIN ClassPapers ON Papers.paperid = ClassPapers.paperid  
  INNER JOIN Class ON ClassPapers.classid = Class.classid 
  INNER JOIN Student ON Class.classid = Student.classid
  INNER JOIN Questions ON Papers.paperid = Questions.paperid
  LEFT JOIN Marks ON Questions.questionid = Marks.questionid AND Student.studentid = Marks.studentid
  LEFT JOIN PreSurvey ON Papers.paperid = PreSurvey.paperid AND Student.studentid = PreSurvey.studentid
  LEFT JOIN PostSurvey ON Papers.paperid = PostSurvey.paperid AND Student.studentid = PostSurvey.studentid
  WHERE Class.classid = %s AND Papers.paperid = %s
  GROUP BY user
  ORDER BY ClassPapers.date DESC", GetSQLValueString($cid, "int"), GetSQLValueString($pid, "int"));
$outstanding = mysql_query($query_outstanding, $learning) or die(mysql_error());
$row_outstanding = mysql_fetch_assoc($outstanding);

$j = 1;


echo "<table class='table table-striped table-bordered'>
<tr><th>Student</th><th>Input Marks</th><th>Answer pre-survey</th><th>Answer post-survey</th></tr>";
if (!empty($row_outstanding)) {
do {
	echo "<tr>";
	echo "<td>".$row_outstanding['user']."</td>";
		echo "<td class='text-center'>";
		if (!empty($row_outstanding['mark'])) { 
			echo "<span class='glyphicon glyphicon-ok' style='color:green' aria-hidden='true'></span>";
		} else {
			echo "<span class='glyphicon glyphicon-remove' style='color:red' aria-hidden='true'></span>";
		}
		echo "</td>";
		echo "<td class='text-center'>";
		if (!empty($row_outstanding['presurvey'])) { 
			echo "<span class='glyphicon glyphicon-ok' style='color:green' aria-hidden='true'></span>";
		} else {
			echo "<span class='glyphicon glyphicon-remove' style='color:red' aria-hidden='true'></span>";
		}
		echo "</td>";
		echo "<td class='text-center'>";
		if (!empty($row_outstanding['postsurvey'])) { 
			echo "<span class='glyphicon glyphicon-ok' style='color:green' aria-hidden='true'></span>";
		} else {
			echo "<span class='glyphicon glyphicon-remove' style='color:red' aria-hidden='true'></span>";
		}
		echo "</td>";
	echo "</tr>";
   } while ($row_outstanding = mysql_fetch_assoc($outstanding));
}
echo "</table>";
?>