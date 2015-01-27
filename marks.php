<?php include('logcheck.php');?>
<?php

if (isset($_POST['formposted']) && $_POST['formposted'] == "insert") {

for ($i = 1; $i < $_POST['totalquestions']; $i++) {
$insertSQL = sprintf("INSERT INTO Marks (questionid, studentid, mark) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['questionnumber'.$i], "int"),
                       GetSQLValueString($row_user['studentid'], "int"),
                       GetSQLValueString($_POST['question'.$i], "int"));

  $Result1 = mysql_query($insertSQL, $learning) or die(mysql_error());
}
  $insertGoTo = "surveys.php";
  header(sprintf("Location: %s", $insertGoTo));


}

if (isset($_POST['formposted']) && $_POST['formposted'] == "update") {

for ($i = 1; $i < $_POST['totalquestions']; $i++) {
$updateSQL = sprintf("UPDATE `Marks` SET `mark`=%s WHERE `studentid`=%s AND `questionid`=%s",
                       GetSQLValueString($_POST['question'.$i], "int"),
                       GetSQLValueString($row_user['studentid'], "int"),
                       GetSQLValueString($_POST['questionnumber'.$i], "int"));

  $Result1 = mysql_query($updateSQL, $learning) or die(mysql_error());
}
  $insertGoTo = "surveys.php";
  header(sprintf("Location: %s", $insertGoTo));


}


$query_papers = sprintf("SELECT papername,Papers.paperid 
  FROM Papers 
  INNER JOIN ClassPapers ON Papers.paperid = ClassPapers.paperid 
  WHERE classid = %s
  ORDER BY ClassPapers.date DESC", GetSQLValueString($row_user['classid'], "int"));
$query_papers = sprintf("SELECT papername,Papers.paperid FROM Papers INNER JOIN ClassPapers ON Papers.paperid = ClassPapers.paperid WHERE classid = %s", GetSQLValueString($row_user['classid'], "int"));
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


    <title>Own Your Learning - Marks</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->


    <link href="css/dashboard.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  <script>
    $(function() {
      
      $("#paperchoice").change(function() {
        $("#marktable").load("selectmarks.php?pid=" + $("#paperchoice").val());
      });
    
    });

    function validate () {

      var elements=document.querySelectorAll('input');
      for(var i=0;i<elements.length;i++){

        var x=elements[i].value;
        if (x == null || x == "") {
        alert("All questions must have marks.");
        return false;
    }
      }

    }

  </script>
  </head>

  <body>
  <?php include('nav.php'); ?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
         <?php include('sidebar.php'); ?> 
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Marks</h1>
          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-md-4 control-label">Select a paper to add or edit marks: </label>
              <div class="col-md-8">
                <select id="paperchoice" class="form-control">
                  <?php
                  if (empty($row_papers['papername'])) {
                   echo "<option>Your teacher has not allocated you any papers yet.</option>";
                  }
                  else {
                    echo "<option>Please select a paper</option>";
                  do {
                    echo "<option value=".$row_papers['paperid'];
                    if ($_POST['pid'] == $row_papers['paperid']) echo " checked ";
                    echo ">".$row_papers['papername']."</option>";
                    } while ($row_papers = mysql_fetch_assoc($papers));
                  }
                  ?>
                </select>
              </div>
            </div>
          </form>
          <form action="marks.php" method="POST" onsubmit="return validate()">
          <div id="marktable"></div>
          <form>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
    <script src="js/bootstrap.min.js"></script>
    <!-- <script src="js/docs.min.js"></script> -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug 
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
