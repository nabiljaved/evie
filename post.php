<?php
$servername = "localhost";
$database = "wordpress";
$username = "root";
$password = "";
$name = "";

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
        
// echo json_encode($email);


if($email){
    //echo $name;
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // echo "Connected successfully";
    $sql = "INSERT INTO wp_newsletter (email) VALUES ('$email')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(
            array('message' => 'Email Inserted Successfully')
        );
    } else {
        echo json_encode(
            array('message' => 'Insertion Failed')
        );
    }
    mysqli_close($conn);
}

?>