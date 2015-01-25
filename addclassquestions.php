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

$query_questions = sprintf("SELECT Papers.papername,questionnumber,Questions.questionid 
FROM Papers 
INNER JOIN Questions ON Papers.paperid = Questions.paperid
WHERE Papers.paperid = %s
ORDER BY Questions.questionnumber", GetSQLValueString($pid, "int"));
$questions = mysql_query($query_questions, $learning) or die(mysql_error());
$row_questions = mysql_fetch_assoc($questions);

$j = 1;

echo "<br /><table class='table table-striped table-bordered'>";
echo "<tr><th>Question Number</th><th>Learnt?</th></tr>";
do {
	echo "<tr>";
	echo "<td>".$row_questions['questionnumber']."</td>
	<td>
	<input name='question".$j."' type='checkbox' value='1'>
	<input name='questionnumber".$j."' type='hidden' value='".$row_questions['questionid']."'>
	</td>";
	$j++;
	echo "</tr>";
   } while ($row_questions = mysql_fetch_assoc($questions));

echo "</table>
<input type='hidden' name='totalquestions' value='".$j."'>
<input type='hidden' name='formposted' value='insert'>
<input type='hidden' name='pid' value='".$pid."''>
<input type='hidden' name='cid' value='".$cid."'>";

echo "<center><button type='submit' class='btn btn-default'>Submit</button></center>";
?>