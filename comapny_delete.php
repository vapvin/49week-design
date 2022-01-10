<?php 

include 'db.php';

$idx = $_POST['idx'];
$company = $_POST['name'];
$sql = mysqli_query($conn,"DELETE FROM company WHERE idx='".$idx."'");
// echo $company;
// print_r($conn);
?>