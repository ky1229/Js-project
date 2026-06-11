<?php 
$cn = new mysqli('localhost','root', '', 'jsproject');

if($cn->connect_error){
die('cant connect:' .$cn->connect_error);
}

/*
this is the connection code for hosted community web hehehehehe
<?php
$host = "sql102.infinityfree.com";     
$user = "if0_42116459";                
$pass = "PYXNY8mkSuimnq"; 
$dbname = "if0_42116459_jsproject";   

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
*/

?>
