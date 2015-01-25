<?php include('logcheckteacher.php');?>
<?php


$query_classes = sprintf("SELECT *
  FROM Class 
  INNER JOIN TeacherClasses ON Class.classid = TeacherClasses.classid 
  WHERE teacherid = %s
  ORDER BY Class.classcode ASC", GetSQLValueString($row_user['teacherid'], "int"));
$classes = mysql_query($query_classes, $learning) or die(mysql_error());
$row_classes = mysql_fetch_assoc($classes);


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

    <title>Own Your Learning - Class Input Progress</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    <script>
    function classlist() {
     $("#paperchoice").load("classpapers.php?cid=" + $("#classchoice").val());
     $("#classprogress").load("blank.php");

    }
    function paperlist() {
     $("#classprogress").load("classinputprogress.php?cid=" + $("#classchoice").val() + "&pid=" + $("#selectchoice").val());

    }
    </script>


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
         <?php include('sidebarteacher.php'); ?> 
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Class Input Progress</h1>
          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-md-4 control-label">Select a paper to add or edit marks: </label>
              <div class="col-md-4">
                <select id="classchoice" class="form-control" onchange="classlist()">
                  <?php
                    echo "<option>Please select a class</option>";
                  do {
                    echo "<option value=".$row_classes['classid'].">".$row_classes['classcode']."</option>";
                    } while ($row_classes = mysql_fetch_assoc($classes));
                  
                  ?>
                </select>
              </div>
              <div class="col-md-4" id="paperchoice">
              </div>
            </div>
          </form>
          <div id='classprogress'></div>
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

