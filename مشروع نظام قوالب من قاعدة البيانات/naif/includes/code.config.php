<?php
mysqli_report(MYSQLI_REPORT_OFF);
@$config = mysqli_connect($host,$user,$pass,$dbname);
mysqli_set_charset($config,'UTF8');
$massege = "<body bgcolor='#f1f1f1'>
<title>System DataBase Error</title>
<center><h1 style='color:red;'>===[ System DataBase Error ]===</h1></center>
</body>
";
if(!$config){
	echo $massege;
	exit();
}
?>