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
<<<<<<< HEAD
$row_questionsTotal = mysql_num_rows($questions);

$j = 1;

echo "<table class='table table-striped table-bordered'>";
echo "<tr><th>Question Number</th><th>Your Mark</th><th>Out of:</th></tr>";
=======

echo "<table class='table table-striped table-bordered'>";
echo "<tr><th>Question Number</th><th>Your Mark</th></tr>";
>>>>>>> 0f5fa7c7a6b2364628be3fd57252880280280c7a
do {
	echo "<tr>";
	echo "<td>".$row_questions['questionnumber']."</td>
	<td>
<<<<<<< HEAD
	<input name='question".$j."' type='text' placeholder='Fill in marks for every question' class='form-control' value='".$row_questions['mark']."'>
	<input name='questionnumber".$j."' type='hidden' value='".$row_questions['questionid']."'>
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
=======
	<input name='".$row_questions['questionid']."' type='text' class='form-control' placeholder='Max: ".$row_questions['marks']."' value='".$row_questions['mark']."'>
	</td>";
	echo "</tr>";

   } while ($row_questions = mysql_fetch_assoc($questions));

echo "</table>";
>>>>>>> 0f5fa7c7a6b2364628be3fd57252880280280c7a
?>