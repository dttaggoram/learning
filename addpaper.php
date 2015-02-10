<?php include('logcheckteacher.php');?>

<?php

$cid = "-1";
if (isset($_GET['cid'])) {
  $cid = $_GET['cid'];
}

$query_papers = sprintf("SELECT * 
	FROM Papers 
    JOIN Class
    LEFT JOIN ClassPapers 
    ON Class.classid = ClassPapers.classid 
    AND Papers.paperid = ClassPapers.paperid
	WHERE ClassPapers.date IS NULL  
	AND Class.classid = %s 
	ORDER BY Papers.paperid", GetSQLValueString($cid, "int"));
$papers = mysql_query($query_papers, $learning) or die(mysql_error());
$row_papers = mysql_fetch_assoc($papers);

echo "<h3>Add a paper</h3>";
echo "<select id='paperchoice' class='form-control' onchange='addpaperquestions(".$cid.")'>";
echo "<option>Please select a paper</option>";
do {
echo "<option value=".$row_papers['paperid'].">".$row_papers['papername']."</option>";
} while ($row_papers = mysql_fetch_assoc($papers));
echo "</select><div id='addpaperload'></div>";
?>