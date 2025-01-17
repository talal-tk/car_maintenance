<?php



$servar ="localhost";
$username ="root";
$password ="12345678";
$dbname="maintenance";
$conn ="";

    $conn =mysqli_connect($server,$username,$password,$dbname);

    if($conn){
        echo"";
    }
    else{
        echo"IS NOT CONNECT";
    }





?>