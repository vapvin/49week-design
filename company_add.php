<?php 

include 'db.php';

$company = $_POST['company'];
$sql = mysqli_query($conn,"INSERT INTO company (name) values('".$company."')");
echo '<script>alert("등록되었습니다."); location.href="/member_group.php"</script>'

?>