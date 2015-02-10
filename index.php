<?php  

if (isset($_POST['user']) && ($_POST['user'] != null)) {
session_start();
$_SESSION['user'] = $_POST['user'];
header( 'Location: http://localhost/learning/home.php' );

}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Own Your Learning</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/cover.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand">Own Your Learning</h3>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading">Own Your Learning</h1>
            <p class="lead">Use your past papers to know how to improve and take control of your revision.</p>
            <center>
              <form action="index.php" method="POST">
                <input type="text" id='user' name='user' class="form-control text-center" style="width:100px" placeholder="5-digit user">
                <br />
                <button type="submit" class="btn btn-lg btn-default">Sign in</button>
              </form>
            </center>
            </p>
          </div>

          <div class="mastfoot">
            <div class="inner">
            <a href="teacherlogin.php">Teacher Login</a>
            </div>
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
