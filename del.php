<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(strlen($_SESSION['sturecmsstuid']==0))
 {
  header('location:logout.php');  
} 
  else
  {
   // Code for deletion
$email=$_SESSION['sturecmsstuid'];
$sql="delete from tblstudent where StudentEmail=:email";
$query=$dbh->prepare($sql);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->execute();
echo "<script>alert('Data deleted');</script>"; 
echo "<script>window.location.href = './login.php'</script>";     
}
session_unset();
?>
