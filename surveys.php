<?php include('logcheck.php');?>
<?php

if (isset($_POST['surveytype']) && $_POST['surveytype'] == 'presurvey') {
  $insertSQL = sprintf("INSERT INTO PreSurvey (paperid, studentid, bestarea) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['paperid'], "int"),
                       GetSQLValueString($row_user['studentid'], "int"),
                       GetSQLValueString($_POST['bestarea'], "text"));

  $Result1 = mysql_query($insertSQL, $learning) or die(mysql_error());
  $insertGoTo = "charts.php";
  header(sprintf("Location: %s", $insertGoTo));
}
if (isset($_POST['surveytype']) && $_POST['surveytype'] == 'postsurvey') {
  $insertSQL = sprintf("INSERT INTO PostSurvey (paperid, studentid, bestarea) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['paperid'], "int"),
                       GetSQLValueString($row_user['studentid'], "int"),
                       GetSQLValueString($_POST['bestarea'], "text"));

  $Result1 = mysql_query($insertSQL, $learning) or die(mysql_error());
  $insertGoTo = "home.php";
  header(sprintf("Location: %s", $insertGoTo));
}

$query_surveys = sprintf("SELECT papername,Papers.paperid,mark,PreSurvey.studentid AS 'presurvey' ,PostSurvey.studentid AS 'postsurvey' 
FROM Papers 
INNER JOIN ClassPapers ON Papers.paperid = ClassPapers.paperid 
INNER JOIN Class ON ClassPapers.classid = Class.classid 
INNER JOIN Student ON Class.classid = Student.classid 
INNER JOIN Questions ON Papers.paperid = Questions.paperid 
LEFT JOIN Marks ON Questions.questionid = Marks.questionid 
LEFT JOIN PreSurvey ON Papers.paperid = PreSurvey.paperid 
LEFT JOIN PostSurvey ON Papers.paperid = PostSurvey.paperid 
WHERE Student.studentid = %s 
GROUP BY papername
ORDER BY ClassPapers.date DESC
LIMIT 1", GetSQLValueString($row_user['studentid'], "int"));
$surveys = mysql_query($query_surveys, $learning) or die(mysql_error());
$row_surveys = mysql_fetch_assoc($surveys);

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

    <title>Own Your Learning - Surveys</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php
    if ($row_surveys['mark'] == null) {
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('You need to type your marks first!')
    window.location.href='http://localhost/learning/marks.php';
    </SCRIPT>");
  }
    ?>
  </head>

  <body>
  <?php include('nav.php'); ?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
         <?php include('sidebar.php'); ?> 
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Surveys</h1>
          <?php
          if (empty($row_surveys['presurvey'])) {
            echo "
          <h2>Pre-Chart Survey for ".$row_surveys['papername']."</h2>
          <form class='form-horizontal' method='POST' action='surveys.php'>
          <input type='hidden' name='surveytype' value='presurvey'>
          <input type='hidden' name='paperid' value='".$row_surveys['paperid']."'>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Please select with area you think you are best at:</label>
              <div class='col-sm-8'>
                <select class='form-control' name='bestarea'>
                  <option>--- Select an area of topics ---</option>
                  <option value='number'>Number - e.g., fractions, percentages, etc</option>
                  <option value='algebra'>Algebra - e.g., letters instead of numbers</option>
                  <option value='ssm'>Shape, Space and Measure - e.g., angles, polygons, etc</option>
                  <option value='data'>Data Handling - e.g., graphs, averages, etc</option>
                </select>
              </div>
            </div>
            <div class='form-group'>
              <div class='col-sm-12'>
                <center>
                  <button type='submit' class='btn btn-default'>Submit</button>
                </center>
              </div>
            </div>
          </form>";
          }
          elseif (empty($row_surveys['postsurvey'])) {
            echo "
          <h2>Post-Chart Survey for ".$row_surveys['papername']."</h2>
          <form class='form-horizontal' method='POST' action='surveys.php'>
          <input type='hidden' name='surveytype' value='postsurvey'>
          <input type='hidden' name='paperid' value='".$row_surveys['paperid']."'>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Please select with area you think you are best at:</label>
              <div class='col-sm-8'>
                <select class='form-control' name='bestarea'>
                  <option value='number'>Number - e.g., fractions, percentages, etc</option>
                  <option value='algebra'>Algebra - e.g., letters instead of numbers</option>
                  <option value='ssm'>Shape, Space and Measure - e.g., angles, polygons, etc</option>
                  <option value='data'>Data Handling - e.g., graphs, averages, etc</option>
                </select>
              </div>
            </div>
            <div class='form-group'>
              <div class='col-sm-12'>
                <center>
                  <button type='submit' class='btn btn-default'>Submit</button>
                </center>
              </div>
            </div>
          </form>";
          }
          else
            echo "<div class='alert alert-success' role='alert'>All surveys are complete! Go to <a href='charts.php' class='alert-link'>charts</a> to look at your results.</div>";
?>
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

