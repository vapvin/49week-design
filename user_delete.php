<?php 

include 'db.php';

$idx = $_POST['idx'];
$email = $_POST['email'];
$company = $_POST['company'];
$sql = mysqli_query($conn,"DELETE FROM user WHERE idx='".$idx."' AND company='".$company."'");

echo "return";
// echo $company;
// print_r($conn);
?>