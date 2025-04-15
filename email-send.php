
<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
session_start();
error_reporting(0);
require 'includes\PHPMailer.php';
require 'includes\SMTP.php';
require 'includes\Exception.php';
 //require 'phpmailer\exceptions';
use PHPmailer\PHPmailer\PHPmailer;
use PHPmailer\PHPmailer\SMTP;
use PHPmailer\PHPmailer\Exception;
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
  } 
else{

//fetching notice title from tblnotice to send in mail via subject
$eid=intval($_GET['nid']);
$sql = $dbh->query("SELECT NoticeTitle FROM `tblnotice` WHERE ID='$eid'");
$fetchsql = $sql->fetch();
$sub = $fetchsql['NoticeTitle'];

//fetching notice
$eid=intval($_GET['nid']);
$sql1 = $dbh->query("SELECT NoticeMsg FROM `tblnotice` WHERE ID='$eid'");
$fetchsql1 = $sql1->fetch();
$msg = $fetchsql1['NoticeMsg'];



$mail=new PHPMailer();
//Create an instance; passing `true` enables exceptions
//$mail = new PHPMailer(true);
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();    	//Send using SMTP
	$mail->Host       = 'smtp.gmail.com';    //Set the SMTP server to send through
	$mail->SMTPAuth   = true;                //Enable SMTP authentication
	$mail->Username   = 'buspass1742@gmail.com';   //SMTP username
    $mail->Password   = 'bus@1742';                //SMTP password
    $mail->SMTPSecure = 'tls';  //Enable implicit TLS encryption(Transport Layer Security (TLS))
    $mail->Port       = '587'; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    //Recipients
    $mail->setFrom('buspass1742@gmail.com', 'YBP-TP & CELL'); //Add again sender emial id
    
    //fetch all students email to send email
    $eid1=8;
    $sth = $dbh->prepare("SELECT StudentEmail FROM `tblstudent` WHERE StudentClass='$eid1'");
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_COLUMN);
    foreach($result as $email)
    {
      $mail->addBCC($email);
    }
    
	//Add a recipient
    //Set email format to HTML
    $mail->IsHTML(true);
   $mail->Subject = $sub;
   $mail->Body    = ' <!doctype html>
<html>
<body style=";
   display: flex;
     justify-content: center;
   align-items: center;
   box-sizing: border-box;
">
 <div style="width: 100%;
   overflow: hidden;
   box-shadow: 0px 7px 22px 0px rgba(0, 0, 0, .1);">  
  <div style="background-color: #f41a;
   width: 100%;
   height: 80px;">
   <h1 style="
   font-size: 20px;
   height: 60px;
   line-height: 60px;
   margin: 0;
   text-align: center;
   color: white;
">YBP NOTICE</h1>
  </div>
  <div Style="width: 100%;
   height: 100%;
   display: flex;
   flex-direction: column;
   justify-content: space-around;
   align-items: inherit;
   flex-wrap: wrap;
   background-color: #F0F8FF;
   padding: 15px;">
    <p style="font-size: 16px;
   padding-left:10px;
   padding-right:20px;
   text-align: justify;
   color: #343434;
   margin-top: 0; 
">'.'Notice Title:'.$sub.'<br><br> Notice Message:'.$msg.'<br><br><br>'.'Note:This is mail from Yashwantrao Bhonsale polytechnic,sawantwadi to notify students every time when some changes have been made in training and placement website.  
 </p>
    <p class="font-size: 20px;
   text-align: inherit;
   color: #343434;
   margin-top: 0;  text-align: center;
   font-style: italic;
   opacity: 0.3;
   margin-bottom: 0;
"></p>
</body></html>';
   // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';   
if(!$mail->send())
{
  echo "<script>alert('Email Not send...Please try agin');</script>"; 
  echo "<script>window.location.href = 'manage-notice.php'</script>";     

} 
else
{
  echo "<script>alert('Email is Send to all students');</script>"; 
  echo "<script>window.location.href = 'manage-notice.php'</script>";     

}
$mail->smtpClose();
}
?>