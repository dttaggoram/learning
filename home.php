<?php include('logcheck.php');?>
<<<<<<< HEAD
<?php

$query_outstanding = sprintf("SELECT papername,mark,PreSurvey.studentid AS 'presurvey' ,PostSurvey.studentid AS 'postsurvey' 
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
  ORDER BY ClassPapers.date DESC", GetSQLValueString($row_user['studentid'], "int"));
$outstanding = mysql_query($query_outstanding, $learning) or die(mysql_error());
$row_outstanding = mysql_fetch_assoc($outstanding);
?>
=======
>>>>>>> 0f5fa7c7a6b2364628be3fd57252880280280c7a
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.png">

    <title>Own Your Learning - Home</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  <?php include('nav.php'); ?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
         <?php include('sidebar.php'); ?> 
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Home</h1>
<<<<<<< HEAD
          <p>Below is a list of all the papers you've done recently. You won't be able to access the next part of the website if any section is not complete.</p>
          <?php
          if (!empty($row_outstanding['mark']) && !empty($row_outstanding['presurvey']) && !empty($row_outstanding['postsurvey'])) {
            echo "<div class='alert alert-success' role='alert'>Up to date!</div>";
          }
          else {
            echo "<div class='alert alert-danger' role='alert'>You still have sections to complete!</div>";
          }
          ?>
          <table class="table table-striped table-bordered">
            <tr>
              <th>Paper</th>
              <th>Record your marks</th>
              <th>Take the pre-chart survey</th>
              <th>View the chart</th>
              <th>Take the post-chart survey</th>
              <?php
              do {
                echo "<tr>
                <td>".$row_outstanding['papername']."</td>
                <td>";
                if (isset($row_outstanding['mark'])) {echo "Complete";}else{echo "<a href='marks.php'>To be done</a>";}
                echo "</td>
                <td>";
                if (isset($row_outstanding['presurvey'])) {echo "Complete";}else{echo "<a href='surveys.php'>To be done</a>";}
                echo "</td>
                <td>";
                if (isset($row_outstanding['mark'])) {echo "<a href='charts.php'>View chart</a>";}else{echo "<a href='marks.php'>Needs marks</a>";}
                echo "</td>
                <td>";
                if (isset($row_outstanding['postsurvey'])) {echo "Complete";}else{echo "<a href='surveys.php'>To be done</a>";}
                echo "</td>
                </tr>";
              } while ($row_outstanding = mysql_fetch_assoc($outstanding));
              ?>
            </tr>
          </table>
=======
          <p><?php echo $_SERVER['PHP_SELF']; ?></p>

          
>>>>>>> 0f5fa7c7a6b2364628be3fd57252880280280c7a
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

