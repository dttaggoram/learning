<?php  

session_start();

if (!isset($_SESSION['user'])) {

header( 'Location: http://localhost/learning' );

}

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_learning = "localhost";
$database_learning = "learning";
$username_learning = "root";
$password_learning = "root";
$learning = mysql_pconnect($hostname_learning, $username_learning, $password_learning) or trigger_error(mysql_error(),E_USER_ERROR); 

$grades = ['A*','A','B','C','D','E','F','G','U'];

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_learning, $learning);

$query_user = sprintf("SELECT studentid,user,classid FROM Student WHERE user = %s", GetSQLValueString($_SESSION['user'], "text"));
$user = mysql_query($query_user, $learning) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);

if (empty($row_user)) {
	header( 'Location: http://localhost/learning' );
}



?>