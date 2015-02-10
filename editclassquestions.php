<?php include('logcheckteacher.php');?>

<?php

$pid = "-1";
if (isset($_GET['pid'])) {
  $pid = $_GET['pid'];
}

$cid = "-1";
if (isset($_GET['cid'])) {
  $cid = $_GET['cid'];
}

$query_questions = sprintf("SELECT Papers.papername,classcode,questionnumber,Questions.questionid,learnt 
FROM Class 
INNER JOIN ClassPapers ON Class.classid = ClassPapers.classid
INNER JOIN Papers ON ClassPapers.paperid = Papers.paperid
INNER JOIN Questions ON Papers.paperid = Questions.paperid
LEFT JOIN ClassQuestionsLearnt ON Questions.questionid = ClassQuestionsLearnt.questionid AND Class.classid = ClassQuestionsLearnt.classid
WHERE Class.classid = %s AND Papers.paperid = %s
GROUP BY Questions.questionid
ORDER BY Questions.questionid", GetSQLValueString($cid, "int"), GetSQLValueString($pid, "int"));
$questions = mysql_query($query_questions, $learning) or die(mysql_error());
$row_questions = mysql_fetch_assoc($questions);

$j = 1;

echo "<h3>".$row_questions['classcode']." - ".$row_questions['papername']."</h3>";
echo "<table class='table table-striped table-bordered'>";
echo "<tr><th>Question Number</th><th>Learnt?</th></tr>";
do {
	echo "<tr>";
	echo "<td>".$row_questions['questionnumber']."</td>
	<td>
	<input name='question".$j."' type='checkbox' value='1'";
	if ($row_questions['learnt'] == 1) echo " checked ";
	echo ">
	<input name='questionnumber".$j."' type='hidden' value='".$row_questions['questionid']."'>
	</td>";
	$j++;
	echo "</tr>";
   } while ($row_questions = mysql_fetch_assoc($questions));

echo "</table>
<input type='hidden' name='totalquestions' value='".$j."'>
<input type='hidden' name='formposted' value='update'>
<input type='hidden' name='pid' value='".$pid."''>
<input type='hidden' name='cid' value='".$cid."'>";
echo "<center><button type='submit' class='btn btn-default'>Submit</button></center>";
?>