<?php 

include 'db.php';

$idx = $_POST['idx'];
$company = $_POST['editcompany'];
$sql = mysqli_query($conn,"UPDATE company SET name='".$company."' WHERE idx='".$idx."'");
// echo $company;
// print_r($conn);
echo '<script>alert("변경되었습니다."); location.href="/member_group.php"</script>'

?>