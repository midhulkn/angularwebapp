<?php
session_start();
   $host        = "host = 127.0.0.1";
   $port        = "port = 8080";
   $dbname      = "dbname = demodatabase";
   $credentials = "user = postgres password=postgres";

$conn = pg_connect( "$host $port $dbname $credentials"  );
$variable = $_SESSION['user'];
$constant ="1";
$username =$variable.$constant;
$sel = pg_query($conn,"select * from $variable");
$sel1 = pg_query($conn,"select * from $username");
$data = array();

while (($row = pg_fetch_array($sel)) && ($row1 = pg_fetch_array($sel1))){
 $data[] = array("lotnum"=>$row['lotno'],"lotidn"=>$row['lotid'],"lotstat"=>$row['lotstat'],"lotnum1"=>$row1['lotno'],"lotidn1"=>$row1['lotid'],"lotstat1"=>$row1['lotstat']);
}

echo json_encode($data);
