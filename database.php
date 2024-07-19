<?php
 
 $conn = new mysqli('localhost' , 'root' , '' , 'book' );

 if(!$conn){
    die(mysqli_error($conn));
 }
?>