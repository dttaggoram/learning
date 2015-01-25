<?php
<<<<<<< HEAD
// Start the session
session_start();
=======
>>>>>>> 0f5fa7c7a6b2364628be3fd57252880280280c7a

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

header( 'Location: http://localhost/learning' );


?>
