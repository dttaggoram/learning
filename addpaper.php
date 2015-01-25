<?php include('logcheckteacher.php');?>

<?php

$cid = "-1";
if (isset($_GET['cid'])) {
  $cid = $_GET['cid'];
}

$query_papers = sprintf("SELECT * 
	FROM Papers 
	LEFT JOIN ClassPapers ON Papers.paperid = ClassPapers.paperid 
	WHERE classid != %s  
	ORDER BY Papers.date", GetSQLValueString($cid, "int"));
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