<?php
session_start(); 

if (!isset($_SESSION['userId']))
{
  echo "<script type = \"text/javascript\">
  window.location = (\"../index.php\");
  </script>";
}

// Check if user is a student
if (!isset($_SESSION['admissionNumber']))
{
  echo "<script type = \"text/javascript\">
  window.location = (\"../index.php\");
  </script>";
}
    
?>
