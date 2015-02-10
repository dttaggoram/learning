<?php include('logcheckteacher.php');?>
<?php

if (isset($_POST['formposted']) && $_POST['formposted'] == "insert") {

$insertSQL = sprintf("INSERT INTO ClassPapers (classid, paperid, date) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['cid'], "int"),
                       GetSQLValueString($_POST['pid'], "int"),
                       GetSQLValueString(date('Y-m-d'), "date"));

$Result1 = mysql_query($insertSQL, $learning) or die(mysql_error());
}

if (isset($_POST['formposted'])) {

$deleteallSQL = sprintf("DELETE FROM `ClassQuestionsLearnt` WHERE `classid` = %s AND `paperid` = %s", 
  GetSQLValueString($_POST['cid'], "int"), GetSQLValueString($_POST['pid'], "int"));
$Result2 = mysql_query($deleteallSQL, $learning) or die(mysql_error());

for ($i = 1; $i < $_POST['totalquestions']; $i++) {
if(!empty($_POST['question'.$i])) $int = "1"; else $int = "0";
$updateSQL = sprintf("INSERT INTO ClassQuestionsLearnt (classid, questionid, paperid, learnt) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['cid'], "int"),
                       GetSQLValueString($_POST['questionnumber'.$i], "int"),
                       GetSQLValueString($_POST['pid'], "int"),
                       GetSQLValueString($int,"int"));

  $Result1 = mysql_query($updateSQL, $learning) or die(mysql_error());
}
  $insertGoTo = "classquestions.php";
  header(sprintf("Location: %s", $insertGoTo));


}

$query_papers = sprintf("SELECT Class.classid,classcode,papername,Papers.paperid,ClassPapers.date
FROM Teachers 
INNER JOIN TeacherClasses ON Teachers.teacherid = TeacherClasses.teacherid
INNER JOIN Class ON TeacherClasses.classid = Class.classid
LEFT JOIN ClassPapers ON Class.classid = ClassPapers.classid
LEFT JOIN Papers ON ClassPapers.paperid = Papers.paperid
WHERE Teachers.teacherid = %s
GROUP BY Class.classid,Papers.paperid
ORDER BY Class.classcode,ClassPapers.date", GetSQLValueString($row_user['teacherid'], "int"));
$papers = mysql_query($query_papers, $learning) or die(mysql_error());
$row_papers = mysql_fetch_assoc($papers);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.png">
    <script src="js/jquery-1.6.min.js"></script>

    <title>Own Your Learning - Teacher Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

<script>
function addpaper(i) {
  $("#loadsection").load("addpaper.php?cid=" + i);
}
function editquestions(j,k) {
  $("#loadsection").load("editclassquestions.php?cid=" + j + "&pid=" + k);
}

function addpaperquestions(j) {
    $("#addpaperload").load("addclassquestions.php?cid=" + j + "&pid=" + $("#paperchoice").val());
}
</script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  <?php include('navteacher.php'); ?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
         <?php include('sidebarteacher.php'); ?> 
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Class Questions Dashboard</h1>
          <p>Click on a paper to view which questions you expect students to complete, or click "Add Paper" to assign a new paper for the class.</p>
          <div class="row">
            <div class="col-sm-3">
          <?php
          $t=1;
          do {

            if ($class != $row_papers['classcode'] && $t>1) {
              echo "
              <a class='list-group-item' onclick='addpaper(".$classid.")'>Add Paper</a>
              </div>
              <h3>Class ".$row_papers['classcode'].": </h3>
              <div class='list-group'>";
            }
            elseif ($class != $row_papers['classcode']) { echo "
              <h3>Class ".$row_papers['classcode'].": </h3>
            <div class='list-group'>";
            $classid = $row_papers['classid'];


            }

            if ($row_papers['papername'] == null) { 
              echo "<a class='list-group-item'>No papers assigned</a>";
            }
            else { 
              echo "<a class='list-group-item' onclick='editquestions(".$row_papers['classid'].",".$row_papers['paperid'].")'>".$row_papers['papername']." - ".date('jS M',strtotime($row_papers['date']))."</a>";
            }

            $class = $row_papers['classcode'];
            $classid = $row_papers['classid'];
            $t++;

          } while ($row_papers = mysql_fetch_assoc($papers));
          echo "<a class='list-group-item' onclick='addpaper(".$classid.")'>Add Paper</a></div>"
          ?>
            </div>
            <form id='loadsection' class="col-sm-9" action="classquestions.php" method="POST">
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

