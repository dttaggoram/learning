<?php include('logcheck.php');?>

<?php

$pid = "-1";
if (isset($_GET['pid'])) {
  $pid = $_GET['pid'];
}

$query_questions = sprintf("SELECT Questions.questionid,questionnumber,mark,marks 
FROM Questions 
LEFT JOIN Marks ON Questions.questionid = Marks.questionid 
LEFT JOIN ClassPapers ON Questions.paperid = ClassPapers.paperid 
LEFT JOIN Class ON ClassPapers.classid = Class.classid
LEFT JOIN Student ON Class.classid = Student.classid 
WHERE Questions.paperid = %s AND Student.studentid = %s", GetSQLValueString($pid, "int"), GetSQLValueString($row_user['studentid'], "int"));
$questions = mysql_query($query_questions, $learning) or die(mysql_error());
$row_questions = mysql_fetch_assoc($questions);

echo "<table class='table table-striped table-bordered'>";
echo "<tr><th>Question Number</th><th>Your Mark</th></tr>";
do {
	echo "<tr>";
	echo "<td>".$row_questions['questionnumber']."</td>
	<td>
	<input name='".$row_questions['questionid']."' type='text' class='form-control' placeholder='Max: ".$row_questions['marks']."' value='".$row_questions['mark']."'>
	</td>";
	echo "</tr>";

   } while ($row_questions = mysql_fetch_assoc($questions));

echo "</table>";
?>