<?php 

include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$username = $_POST['username'];
$company = $_POST['company'];

// echo $email;
// echo $password;
// echo $password2;
// echo $username;
// echo $company;

if($password != $password2){
    echo '<script>alert("비밀번호가 일치하지 않습니다."); location.href="/member_list.php"</script>';
} else {
    $sql = mysqli_query($conn,"INSERT INTO user (username, password, company, email) values('".$username."', '".$password."', '".$company."', '".$email."')");
    echo '<script>alert("등록되었습니다."); location.href="/member_list.php"</script>';

}


?>