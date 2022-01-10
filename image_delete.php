<?php 

include 'db.php';

$title = $_POST['title'];
$type = $_POST['type'];
$company = $_POST['company'];
$sql = mysqli_query($conn,"DELETE FROM images WHERE title='".$title."' AND type='".$type."' AND company='".$company."'");
// echo $company;
// print_r($conn);
?>