 <?php
   $host        = "host = 127.0.0.1";
   $port        = "port = 8080";
   $dbname      = "dbname = demodatabase";
   $credentials = "user = postgres password=postgres";

$connect = pg_connect( "$host $port $dbname $credentials"  );
$data    = json_decode(file_get_contents("php://input"));
if (count($data) > 0) {
    $name     = pg_escape_string($connect, $data->name);
    $email    = pg_escape_string($connect, $data->email);
    $age      = pg_escape_string($connect, $data->age);
    $btn_name = $data->btnName;
    if ($btn_name == 'Update') {
        $id    = $data->id;
        $query = "UPDATE newtest SET name = '$name', email = '$email', age = '$age' WHERE id = '$id'";
        if (pg_query($connect, $query)) {
            echo 'Updated Successfully...';
        } else {
            echo 'Failed';
        }
    }
}
?>