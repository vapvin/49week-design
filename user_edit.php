<?php 

include 'db.php';

$idx = $_POST['idx'];
$email = $_POST['emailedit'];
$password1 = $_POST['passwordedit'];
$password2 = $_POST['passwordedit2'];
$username = $_POST['usernameedit'];
$company = $_POST['companyedit'];

if($password1 != $password2){
    echo '<script>alert("비밀번호가 일치하지 않습니다."); location.href="/member_list.php"</script>';
} else {
    $sql = mysqli_query($conn,"UPDATE user SET company='".$company."', username='".$username."', email='".$email."', password='".$password1."' WHERE email='".$email."'");
    echo '<script>alert("변경되었습니다."); location.href="/member_list.php"</script>';

}

?>