<?php include('logcheck.php');?>

<?php

$pid = "-1";
if (isset($_GET['pid'])) {
  $pid = $_GET['pid'];
}

$query_questions = sprintf("SELECT Questions.questionid,questionnumber,mark,marks 
FROM Questions 
JOIN Student
LEFT JOIN Marks ON Questions.questionid = Marks.questionid AND Student.studentid = Marks.studentid
WHERE Questions.paperid = %s AND (Student.studentid = %s OR Student.studentid IS NULL)", GetSQLValueString($pid, "int"), GetSQLValueString($row_user['studentid'], "int"));
$questions = mysql_query($query_questions, $learning) or die(mysql_error());
$row_questions = mysql_fetch_assoc($questions);
$row_questionsTotal = mysql_num_rows($questions);

$j = 1;

echo "<table class='table table-striped table-bordered'>";
echo "<tr><th>Question Number</th><th>Your Mark</th><th>Out of:</th></tr>";
do {
	echo "<tr>";
	echo "<td>".$row_questions['questionnumber']."</td>
	<td>
	<input name='question".$j."' type='text' placeholder='Fill in marks for every question' class='form-control' value='".$row_questions['mark']."'>
	<input name='questionnumber".$j."' type='hidden' value='".$row_questions['questionid']."'>
	<input class='maxmarks' name='marks".$j."' type='hidden' value='".$row_questions['marks']."'>
	</td>
	<td>Max: ".$row_questions['marks']."
	</td>";
	$j++;
	echo "</tr>";
	$update = $row_questions['mark'];
   } while ($row_questions = mysql_fetch_assoc($questions));

echo "</table>
<input type='hidden' name='totalquestions' value='".$j."'>";
if ($update == "") echo "<input type='hidden' name='formposted' value='insert'>";
else echo "<input type='hidden' name='formposted' value='update'>";
echo "<center><button type='submit' class='btn btn-default'>Submit</button></center>";