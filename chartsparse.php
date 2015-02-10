<?php include('logcheck.php');?>
<?php

$pid = "-1";
if (isset($_GET['pid'])) {
  $pid = $_GET['pid'];
}

$query_charts = sprintf("SELECT Marks.mark AS QuestionMark,learnt,questionnumber,functional,marks,topicmarks,area,supertopic,topic,grade,mwclip 
FROM Marks 
INNER JOIN Questions ON Marks.questionid = Questions.questionid
INNER JOIN Student ON Marks.studentid = Student.studentid
LEFT JOIN ClassQuestionsLearnt ON Student.classid = ClassQuestionsLearnt.classid AND Questions.questionid = ClassQuestionsLearnt.questionid
INNER JOIN QuestionTopics ON Marks.questionid = QuestionTopics.questionid
INNER JOIN Topics ON QuestionTopics.topicid = Topics.topicid
WHERE Marks.studentid = %s AND Questions.paperid = %s
ORDER BY grade DESC,area,supertopic", GetSQLValueString($row_user['studentid'], "int"), GetSQLValueString($pid, "int"));
$charts = mysql_query($query_charts, $learning) or die(mysql_error());
$row_charts = mysql_fetch_assoc($charts);

$data = array();
    
    for ($x = 0; $x < mysql_num_rows($charts); $x++) {
        $data[] = mysql_fetch_assoc($charts);
    }
    
echo json_encode($data);
?>