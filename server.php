  <?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
if(!isset($_POST)) die();
session_start();
$response = [];
$dbconn = pg_connect("host=localhost port=8080 dbname=demodatabase user=postgres");
$username = pg_escape_string($dbconn, $_POST['username']);
$password = pg_escape_string($dbconn, $_POST['password']);
$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = pg_query($dbconn, $query);
if(pg_num_rows($result) > 0) {
if($username==admin && $password==admin123)
{
	$response['status'] = 'adminlogged';
	$response['user'] = $username;
	$response['id'] = md5(uniqid());
	$_SESSION['id'] = $response['id'];
	$_SESSION['user'] = $username;
}else{
	$response['status'] = 'loggedin';
	$response['user'] = $username;
	$response['id'] = md5(uniqid());
	$_SESSION['id'] = $response['id'];
	$_SESSION['user'] = $username;
}
}
else {
	$response['status'] = 'error';
	}

echo json_encode($response);