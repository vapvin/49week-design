<?php 

include 'db.php';

$company = $_POST['type_compnay'];
$type = $_POST['add_type'];
$sql = mysqli_query($conn,"INSERT INTO types (name, company) values('".$type."','".$company."')");
echo '<script>alert("추가되었습니다."); history.back();</script>'


?>