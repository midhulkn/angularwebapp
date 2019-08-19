<?php  
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
if(!isset($_POST)) die();
session_start();
$response = [];
   $host        = "host = 127.0.0.1";
   $port        = "port = 8080";
   $dbname      = "dbname = demodatabase";
   $credentials = "user = postgres password=postgres";
   $Tablename = $_POST['Tablename'];
   $db = pg_connect( "$host $port $dbname $credentials"  );
   $sql =<<<EOF
      CREATE TABLE $Tablename
      (LOTNO INT PRIMARY KEY     NOT NULL,
		LOTID           TEXT,
		LOTSTAT           TEXT    );
		INSERT INTO $Tablename (lotno)
      SELECT i 
      FROM generate_series(1,100) i;
EOF;

   $ret = pg_query($db, $sql);
   if(!$ret) {
      $response['status'] = 'error';
   } else {
      $response['status'] = 'posted';
   }
   echo json_encode($response);
   
?>