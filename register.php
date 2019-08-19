  <?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
if(!isset($_POST)) die();
session_start();
$response = [];
$dbconn = pg_connect("host=localhost port=8080 dbname=demodatabase user=postgres");
$username = pg_escape_string($dbconn, $_POST['username']);
$email = pg_escape_string($dbconn, $_POST['email']);
$password = pg_escape_string($dbconn, $_POST['password']);
$check=pg_query($dbconn,"select * from users where username='$username'");
    $checkrows=pg_num_rows($check);

   if($checkrows>0) {
      $response['status'] = 'Username Exist';
   } else {  
$query = ("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
$result = pg_query($dbconn, $query);
if ($result == True) {
	$response['status'] = 'loggedin';
}
else {
	$response['status'] = 'error';
	}
}
echo json_encode($response);


?>
